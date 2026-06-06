<?php

use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::middleware(['can:gestionar_usuarios'])->group(function () {
        Route::get('users', UsersIndex::class)->name('users.index');
    });

    Route::middleware(['can:gestionar_mesas'])->group(function () {
        Route::get('pos/tables', \App\Livewire\Pos\Tables::class)->name('pos.tables');
        Route::get('pos/orders/{table}', \App\Livewire\Pos\TableOrder::class)->name('pos.orders.create');
    });

    Route::middleware(['can:gestionar_mesas'])->group(function () {
        Route::get('pos/orders', function () {
            return redirect()->route('pos.tables');
        })->name('pos.orders.index');
    });

    Route::middleware(['can:ver_kds'])->group(function () {
        Route::get('kitchen', \App\Livewire\Kitchen\Index::class)->name('kitchen.index');
    });

    Route::middleware(['can:editar_menu'])->group(function () {
        Route::get('menu/categories', \App\Livewire\Menu\Index::class)->name('menu.categories');
    });

    Route::middleware(['can:gestionar_usuarios'])->group(function () {
        Route::get('customers', \App\Livewire\Customers\Index::class)->name('customers.index');
    });

    Route::middleware(['can:ver_caja'])->group(function () {
        Route::get('cashier', \App\Livewire\Cashier\Index::class)->name('cashier.index');
        Route::get('cashier/receipt/{order}', function (\App\Models\Order $order) {
            $payment = $order->payments()->where('status', 'approved')->first();
            $invoice = $order->invoices()->first();
            $restaurant = [
                'name' => env('SRI_NOMBRE_COMERCIAL', 'Solemia'),
                'ruc' => env('SRI_RUC', '9999999999999'),
                'address' => env('SRI_DIR_MATRIZ', 'Av. Principal'),
                'phone' => env('APP_PHONE', ''),
            ];
            return view('cashier.receipt', compact('order', 'payment', 'invoice', 'restaurant'));
        })->name('cashier.receipt');
    });

    Route::middleware(['can:ver_inventario'])->group(function () {
        Route::view('inventory', 'livewire.inventory.index')->name('inventory.index');
    });

    Route::middleware(['can:ver_reportes'])->group(function () {
        Route::view('reports', 'livewire.reports.index')->name('reports.index');
    });

    Route::middleware(['can:configurar_sistema'])->group(function () {
        Route::view('settings', 'livewire.settings.index')->name('settings.index');
    });
});

require __DIR__.'/auth.php';
