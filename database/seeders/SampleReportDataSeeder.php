<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TableModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleReportDataSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if data already exists (idempotent)
        if (Order::count() > 10) {
            $this->command->info('Datos de prueba ya existen. Saltando...');
            return;
        }

        $cashier = User::role('Cajero')->first() ?? User::first();
        $waiter = User::role('Mesero')->first() ?? User::first();
        $products = Product::where('is_available', true)->get();
        $tables = TableModel::all();

        if ($products->isEmpty()) {
            $this->command->warn('No hay productos. Ejecuta MenuSeeder primero.');
            return;
        }

        $paymentMethods = ['cash', 'credit_card', 'debit_card', 'qr_wallet'];
        $totalOrders = 0;
        $totalRegisters = 0;

        for ($daysAgo = 90; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo)->startOfDay();

            if ($date->isFuture()) continue;

            // Restaurant closed on Sundays (day 6 of week)
            if ($daysAgo % 7 === 6) continue;

            // === Open daily cash register ===
            $openAt = (clone $date)->addHours(rand(7, 9))->addMinutes(rand(0, 30));
            $openingAmount = 150 + rand(0, 10) * 10;

            $register = CashRegister::create([
                'user_id' => $cashier->id,
                'name' => 'Caja Principal',
                'opening_amount' => $openingAmount,
                'status' => 'open',
                'opened_at' => $openAt,
            ]);

            $dayTotalPayments = 0;

            // 3–8 orders per day (more on weekends)
            $isWeekend = in_array($date->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY]);
            $ordersPerDay = $isWeekend ? rand(5, 8) : rand(3, 6);

            for ($i = 0; $i < $ordersPerDay; $i++) {
                $hour = rand(8, 22);
                $minute = rand(0, 59);
                $openedAt = (clone $date)->addHours($hour)->addMinutes($minute);

                // Skip if order would be after register close (11 PM)
                if ($openedAt->hour >= 23) continue;

                $duration = rand(15, 120);
                $closedAt = (clone $openedAt)->addMinutes($duration);

                $table = $tables->random();
                $numItems = rand(1, 5);
                $selectedProducts = $products->random(min($numItems, $products->count()));
                $subtotal = 0;
                $items = [];

                foreach ($selectedProducts as $p) {
                    $qty = rand(1, 3);
                    $price = $p->base_price;
                    $items[] = [
                        'product_id' => $p->id,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'subtotal' => $qty * $price,
                        'kitchen_status' => 'ready',
                        'kitchen_area' => $p->kitchen_area ?? 'cocina',
                        'sent_at' => (clone $openedAt)->addMinutes(rand(1, 5)),
                        'ready_at' => (clone $openedAt)->addMinutes(rand(5, 25)),
                    ];
                    $subtotal += $qty * $price;
                }

                $discount = rand(0, 4) === 0 ? round($subtotal * rand(5, 15) / 100, 2) : 0;
                $tax = round(($subtotal - $discount) * 0.12, 2);
                $tip = rand(0, 3) === 0 ? round($subtotal * rand(5, 15) / 100, 2) : 0;
                $total = round($subtotal - $discount + $tax + $tip, 2);

                $order = Order::create([
                    'table_id' => $table->id,
                    'user_id' => $waiter->id,
                    'cashier_id' => $cashier->id,
                    'cash_register_id' => $register->id,
                    'type' => $i < 2 ? 'takeaway' : 'dine_in',
                    'status' => 'complete',
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'tip' => $tip,
                    'total' => $total,
                    'opened_at' => $openedAt,
                    'closed_at' => $closedAt,
                ]);

                foreach ($items as $item) {
                    $order->items()->create($item);
                }

                $method = $paymentMethods[array_rand($paymentMethods)];
                Payment::create([
                    'order_id' => $order->id,
                    'cash_register_id' => $register->id,
                    'method' => $method,
                    'amount' => $total,
                    'status' => 'approved',
                    'processed_by' => $cashier->id,
                    'processed_at' => $closedAt,
                ]);

                $dayTotalPayments += $total;

                if (rand(1, 100) <= 60) {
                    Invoice::create([
                        'order_id' => $order->id,
                        'type' => 'factura',
                        'sequential' => '001-' . str_pad((string)($totalOrders + 1), 9, '0', STR_PAD_LEFT),
                        'sri_status' => 'draft',
                        'customer_name' => fake()->name(),
                        'customer_ruc' => fake()->numerify('##########001'),
                    ]);
                }

                $totalOrders++;
            }

            // === Close daily cash register ===
            if ($dayTotalPayments > 0) {
                $closeAt = (clone $date)->addHours(23)->addMinutes(rand(0, 30));
                $expected = round($openingAmount + $dayTotalPayments, 2);
                $diff = round(rand(-5, 5) + rand(0, 99) / 100, 2);
                $closingAmount = round($expected + $diff, 2);

                $register->update([
                    'status' => 'closed',
                    'expected_amount' => $expected,
                    'closing_amount' => $closingAmount,
                    'difference' => $diff,
                    'closed_at' => $closeAt,
                ]);

                $totalRegisters++;
            } else {
                // No sales — delete the empty register
                $register->delete();
            }
        }

        $this->command->info("{$totalOrders} órdenes y {$totalRegisters} cierres de caja generados correctamente.");
    }
}
