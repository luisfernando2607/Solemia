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
        $cashier = User::role('Cajero')->first() ?? User::first();
        $waiter = User::role('Mesero')->first() ?? User::first();
        $products = Product::where('is_available', true)->get();
        $tables = TableModel::all();

        if ($products->isEmpty()) {
            $this->command->warn('No hay productos. Ejecuta MenuSeeder primero.');
            return;
        }

        $paymentMethods = ['cash', 'credit_card', 'debit_card', 'qr_wallet'];

        // Create a cash register for the cashier
        $register = CashRegister::firstOrCreate(
            ['user_id' => $cashier->id, 'status' => 'open'],
            [
                'name' => 'Caja Principal',
                'opening_amount' => 200.00,
                'opened_at' => now()->subDays(60),
            ]
        );

        $ordersCount = 0;

        for ($daysAgo = 60; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo)->startOfDay();

            // Skip future dates
            if ($date->isFuture()) continue;

            // Some days off (restaurant closed)
            if ($daysAgo % 7 === 6) continue;

            // 2-5 orders per day
            $ordersPerDay = rand(2, 5);

            for ($i = 0; $i < $ordersPerDay; $i++) {
                $hour = rand(8, 21);
                $minute = rand(0, 59);
                $openedAt = (clone $date)->addHours($hour)->addMinutes($minute);
                $duration = rand(15, 90);
                $closedAt = (clone $openedAt)->addMinutes($duration);

                $table = $tables->random();
                $numItems = rand(1, 4);
                $selectedProducts = $products->random($numItems);
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
                        'ready_at' => (clone $openedAt)->addMinutes(rand(5, 20)),
                    ];
                    $subtotal += $qty * $price;
                }

                $discount = rand(0, 5) === 0 ? round($subtotal * rand(5, 15) / 100, 2) : 0;
                $tax = round(($subtotal - $discount) * 0.12, 2);
                $tip = rand(0, 3) === 0 ? round($subtotal * rand(5, 15) / 100, 2) : 0;
                $total = round($subtotal - $discount + $tax + $tip, 2);

                $order = Order::create([
                    'table_id' => $table->id,
                    'user_id' => $waiter->id,
                    'cashier_id' => $cashier->id,
                    'cash_register_id' => $register->id,
                    'type' => 'dine_in',
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

                Payment::create([
                    'order_id' => $order->id,
                    'cash_register_id' => $register->id,
                    'method' => $paymentMethods[array_rand($paymentMethods)],
                    'amount' => $total,
                    'status' => 'approved',
                    'processed_by' => $cashier->id,
                    'processed_at' => $closedAt,
                ]);

                // 60% chance of creating an invoice
                if (rand(1, 100) <= 60) {
                    Invoice::create([
                        'order_id' => $order->id,
                        'type' => 'factura',
                        'sequential' => '001-' . str_pad((string)($ordersCount + 1), 9, '0', STR_PAD_LEFT),
                        'sri_status' => 'draft',
                        'customer_name' => fake()->name(),
                        'customer_ruc' => fake()->numerify('##########001'),
                    ]);
                }

                $ordersCount++;
            }
        }

        $this->command->info("{$ordersCount} órdenes de prueba generadas correctamente.");
    }
}
