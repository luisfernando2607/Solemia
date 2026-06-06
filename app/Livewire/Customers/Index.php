<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingCustomer = false;

    public $customerName = '';
    public $customerRuc = '';
    public $customerPhone = '';
    public $customerEmail = '';
    public $customerAddress = '';
    public $customerNotes = '';
    public $customerActive = true;

    public function mount(): void
    {
        $this->search = request('search', '');
    }

    public function getCustomersProperty()
    {
        return Customer::orderBy('name')
            ->when($this->search, fn($q) => $q->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('ruc', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->paginate(15);
    }

    public function showCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingCustomer = false;
    }

    public function showEditForm(Customer $customer): void
    {
        $this->customerName = $customer->name;
        $this->customerRuc = $customer->ruc ?? '';
        $this->customerPhone = $customer->phone ?? '';
        $this->customerEmail = $customer->email ?? '';
        $this->customerAddress = $customer->address ?? '';
        $this->customerNotes = $customer->notes ?? '';
        $this->customerActive = $customer->is_active;
        $this->editingCustomer = $customer;
        $this->showForm = true;
    }

    public function resetForm(): void
    {
        $this->customerName = '';
        $this->customerRuc = '';
        $this->customerPhone = '';
        $this->customerEmail = '';
        $this->customerAddress = '';
        $this->customerNotes = '';
        $this->customerActive = true;
        $this->editingCustomer = false;
        $this->showForm = false;
    }

    public function save(): void
    {
        $this->validate([
            'customerName' => 'required|string|max:150',
            'customerRuc' => 'nullable|string|max:13|unique:customers,ruc,' . ($this->editingCustomer?->id ?? ''),
            'customerPhone' => 'nullable|string|max:20',
            'customerEmail' => 'nullable|email|max:150',
        ]);

        $data = [
            'name' => $this->customerName,
            'ruc' => $this->customerRuc ?: null,
            'phone' => $this->customerPhone ?: null,
            'email' => $this->customerEmail ?: null,
            'address' => $this->customerAddress ?: null,
            'notes' => $this->customerNotes ?: null,
            'is_active' => $this->customerActive,
        ];

        if ($this->editingCustomer) {
            $this->editingCustomer->update($data);
            $this->dispatch('swal', icon: 'success', title: 'Cliente actualizado');
        } else {
            Customer::create($data);
            $this->dispatch('swal', icon: 'success', title: 'Cliente creado');
        }

        $this->resetForm();
    }

    public function confirmDelete($id): void
    {
        $this->dispatch('confirm', [
            'title' => '¿Eliminar cliente?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmText' => 'Sí, eliminar',
            'cancelText' => 'Cancelar',
            'callback' => 'delete',
            'params' => ['id' => $id],
        ]);
    }

    #[On('delete')]
    public function delete($id): void
    {
        $customer = Customer::find($id);
        if (!$customer) return;

        $customer->delete();
        $this->dispatch('swal', icon: 'success', title: 'Cliente eliminado');
    }

    public function render()
    {
        return view('livewire.customers.index')
            ->layout('layouts.app');
    }
}
