<?php

namespace App\Livewire\Cashier;

use App\Mail\InvoiceMail;
use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Services\SriInvoiceGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Index extends Component
{
    public ?CashRegister $activeRegister = null;
    public $showOpenForm = false;
    public $openingAmount = 0;
    public $registerName = '';

    public $selectedOrderId = null;
    public $paymentMethod = 'cash';
    public $cashTendered = 0;
    public $cashChange = 0;
    public $tipAmount = 0;
    public $tipMode = 'none';
    public $discountType = 'none';
    public $discountValue = 0;
    public $showInvoiceForm = false;
    public $invoiceCustomerName = '';
    public $invoiceCustomerRuc = '';
    public $invoiceCustomerEmail = '';
    public $invoiceCustomerAddress = '';
    public $sendInvoiceEmail = false;
    public $customerSearch = '';
    public $showCustomerResults = false;

    public $lastProcessedOrderId = null;
    public $pendingInvoiceName = '';
    public $pendingInvoiceRuc = '';
    public $pendingInvoiceEmail = '';
    public $pendingInvoiceAddress = '';
    public $pendingSendEmail = false;

    protected $listeners = ['refreshCashier' => '$refresh'];

    public function mount(): void
    {
        $this->activeRegister = CashRegister::where('status', 'open')->first();
    }

    public function getOrdersForBillingProperty()
    {
        return Order::whereIn('status', ['sent', 'partial', 'complete'])
            ->whereDoesntHave('payments', fn($q) => $q->where('status', 'approved'))
            ->with(['table', 'user', 'items' => fn($q) => $q->with('product')])
            ->orderBy('created_at')
            ->get();
    }

    public function getSelectedOrderProperty()
    {
        if (!$this->selectedOrderId) return null;
        return Order::with(['table', 'user', 'items' => fn($q) => $q->with('product'), 'payments'])->find($this->selectedOrderId);
    }

    public function selectOrder($orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->resetPaymentForm();
    }

    public function getCustomerResultsProperty()
    {
        if (strlen($this->customerSearch) < 2) return collect();
        return Customer::where('is_active', true)
            ->where(function($q) {
                $q->where('name', 'like', "%{$this->customerSearch}%")
                  ->orWhere('ruc', 'like', "%{$this->customerSearch}%")
                  ->orWhere('phone', 'like', "%{$this->customerSearch}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    public function selectCustomer($id): void
    {
        $customer = Customer::find($id);
        if (!$customer) return;
        $this->invoiceCustomerName = $customer->name;
        $this->invoiceCustomerRuc = $customer->ruc ?? '';
        $this->invoiceCustomerEmail = $customer->email ?? '';
        $this->invoiceCustomerAddress = $customer->address ?? '';
        $this->customerSearch = $customer->name;
        $this->showCustomerResults = false;
        $this->showInvoiceForm = true;
    }

    public function updatedCustomerSearch(): void
    {
        $this->showCustomerResults = true;
    }

    public function resetPaymentForm(): void
    {
        $this->paymentMethod = 'cash';
        $this->cashTendered = 0;
        $this->cashChange = 0;
        $this->tipAmount = 0;
        $this->tipMode = 'none';
        $this->discountType = 'none';
        $this->discountValue = 0;
        $this->showInvoiceForm = false;
        $this->sendInvoiceEmail = false;
        $this->customerSearch = '';
        $this->showCustomerResults = false;
    }

    public function updatedCashTendered(): void
    {
        $order = $this->selectedOrder;
        if (!$order || $this->paymentMethod !== 'cash') return;
        $total = $order->total + $this->tipAmount;
        $this->cashChange = max(0, (float)$this->cashTendered - $total);
    }

    public function updatedTipMode($value): void
    {
        $order = $this->selectedOrder;
        if (!$order) return;
        $this->tipAmount = match ($value) {
            'ten' => round($order->subtotal * 0.10, 2),
            'fifteen' => round($order->subtotal * 0.15, 2),
            'twenty' => round($order->subtotal * 0.20, 2),
            default => 0,
        };
        $this->updatedCashTendered();
    }

    public function getOrderTotalProperty()
    {
        $order = $this->selectedOrder;
        if (!$order) return 0;
        $discount = $this->discountType === 'percent'
            ? round($order->subtotal * ($this->discountValue / 100), 2)
            : ($this->discountType === 'fixed' ? $this->discountValue : 0);
        return $order->subtotal + $order->tax - $discount + $this->tipAmount;
    }

    // Cash Register
    public function showOpenRegister(): void
    {
        $this->showOpenForm = true;
        $this->openingAmount = 0;
        $this->registerName = 'Caja ' . now()->format('d/m/Y');
    }

    public function openRegister(): void
    {
        $this->validate([
            'openingAmount' => 'required|numeric|min:0',
            'registerName' => 'required|string|max:100',
        ]);

        $this->activeRegister = CashRegister::create([
            'user_id' => Auth::id(),
            'name' => $this->registerName,
            'opening_amount' => $this->openingAmount,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        $this->showOpenForm = false;
        $this->js("Swal.fire({icon: 'success', title: 'Caja abierta', text: 'Turno iniciado con \$$this->openingAmount', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
    }

    public function closeRegister(): void
    {
        if (!$this->activeRegister) return;

        $totalSales = Payment::where('cash_register_id', $this->activeRegister->id)
            ->where('status', 'approved')->sum('amount');
        $transactions = Payment::where('cash_register_id', $this->activeRegister->id)
            ->where('status', 'approved')->count();

        $expected = (float)$this->activeRegister->opening_amount + (float)$totalSales;
        $counted = $expected;

        $this->activeRegister->update([
            'closing_amount' => $counted,
            'expected_amount' => $expected,
            'difference' => $counted - $expected,
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $this->js("Swal.fire({icon: 'success', title: 'Caja cerrada', text: 'Total esperado: \$$expected | Diferencia: $" . number_format($counted - $expected, 2) . " | Transacciones: $transactions', timer: 3000})");

        $this->activeRegister = null;
    }

    public function getRegisterTotalSalesProperty()
    {
        if (!$this->activeRegister) return 0;
        return Payment::where('cash_register_id', $this->activeRegister->id)
            ->where('status', 'approved')->sum('amount');
    }

    public function getRegisterTransactionsCountProperty()
    {
        if (!$this->activeRegister) return 0;
        return Payment::where('cash_register_id', $this->activeRegister->id)
            ->where('status', 'approved')->count();
    }

    // Payment
    public function processPayment(): void
    {
        if (!$this->selectedOrderId) {
            $this->js("Swal.fire({icon: 'error', title: 'Selecciona una orden', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500})");
            return;
        }
        if (!$this->activeRegister) {
            $this->js("Swal.fire({icon: 'error', title: 'Abre una caja primero', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500})");
            return;
        }

        $order = Order::with(['table', 'items'])->find($this->selectedOrderId);
        if (!$order) {
            $this->js("Swal.fire({icon: 'error', title: 'Orden no encontrada', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500})");
            return;
        }

        $total = (float)$order->subtotal + (float)$order->tax;
        $discount = 0;
        if ($this->discountType === 'percent') {
            $discount = round($order->subtotal * ($this->discountValue / 100), 2);
        } elseif ($this->discountType === 'fixed') {
            $discount = (float)$this->discountValue;
        }
        $total = $total - $discount + (float)$this->tipAmount;

        if ($this->paymentMethod === 'cash' && (float)$this->cashTendered < $total) {
            $this->js("Swal.fire({icon: 'error', title: 'Efectivo insuficiente', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500})");
            return;
        }

        try {
            DB::transaction(function () use ($order, $total, $discount) {
                Payment::create([
                    'order_id' => $order->id,
                    'cash_register_id' => $this->activeRegister->id,
                    'method' => $this->paymentMethod,
                    'amount' => $total,
                    'cash_tendered' => $this->paymentMethod === 'cash' ? $this->cashTendered : null,
                    'cash_change' => $this->paymentMethod === 'cash' ? ($this->cashTendered - $total) : null,
                    'status' => 'approved',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);

                $order->update([
                    'status' => 'complete',
                    'closed_at' => now(),
                    'discount' => $discount,
                    'tip' => $this->tipAmount,
                    'total' => $total,
                    'cashier_id' => Auth::id(),
                    'cash_register_id' => $this->activeRegister->id,
                ]);

                if ($order->relationLoaded('table') && $order->table) {
                    $order->table->update(['status' => 'available']);
                }
            });

            $this->pendingInvoiceName = $this->invoiceCustomerName;
            $this->pendingInvoiceRuc = $this->invoiceCustomerRuc;
            $this->pendingInvoiceEmail = $this->invoiceCustomerEmail;
            $this->pendingInvoiceAddress = $this->invoiceCustomerAddress;
            $this->pendingSendEmail = $this->sendInvoiceEmail;

            $this->lastProcessedOrderId = $order->id;
            $this->selectedOrderId = null;
            $this->resetPaymentForm();

            $this->js("Swal.fire({icon: 'success', title: 'Pago procesado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true})");
        } catch (\Throwable $e) {
            Log::error('Payment processing failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            $this->js("Swal.fire({icon: 'error', title: 'Error al procesar pago', text: '{$e->getMessage()}', timer: 4000})");
        }
    }

    public function generateInvoice(): void
    {
        if (!$this->lastProcessedOrderId) return;
        if (!$this->pendingInvoiceName) return;

        try {
            $order = Order::with('items.product', 'payments')->find($this->lastProcessedOrderId);
            if (!$order) return;

            $generator = new SriInvoiceGenerator();
            $accessKey = $generator->generateXml(
                $order,
                $this->pendingInvoiceName,
                $this->pendingInvoiceRuc,
                $this->pendingInvoiceEmail,
                $this->pendingInvoiceAddress,
            );
            $invoice = Invoice::where('access_key', $accessKey)->first();

            if ($invoice && $this->pendingSendEmail && $this->pendingInvoiceEmail) {
                try {
                    Mail::to($this->pendingInvoiceEmail)->send(new InvoiceMail($order, $invoice));
                } catch (\Exception $e) {
                    Log::warning('Invoice email: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Invoice gen: ' . $e->getMessage());
        }
    }

    public function printReceipt($orderId): void
    {
        $this->dispatch('open-receipt', orderId: $orderId);
    }

    public function render()
    {
        return view('livewire.cashier.index')
            ->layout('layouts.app');
    }
}
