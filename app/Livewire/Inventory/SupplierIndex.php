<?php

namespace App\Livewire\Inventory;

use App\Models\Supplier;
use Livewire\Component;

class SupplierIndex extends Component
{
    public $showForm = false;
    public $editing = false;
    public $supplierId = null;
    public $name = '';
    public $ruc = '';
    public $phone = '';
    public $email = '';
    public $contactPerson = '';
    public $paymentTerms = '';
    public $notes = '';
    public $isActive = true;
    public $search = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'ruc' => 'nullable|string|max:13',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'contactPerson' => 'nullable|string|max:100',
            'paymentTerms' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function getSuppliersProperty()
    {
        return Supplier::where(function ($q) {
            if ($this->search) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('ruc', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            }
        })->orderBy('name')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editing = false;
    }

    public function edit($id): void
    {
        $s = Supplier::findOrFail($id);
        $this->supplierId = $s->id;
        $this->name = $s->name;
        $this->ruc = $s->ruc;
        $this->phone = $s->phone;
        $this->email = $s->email;
        $this->contactPerson = $s->contact_person;
        $this->paymentTerms = $s->payment_terms;
        $this->notes = $s->notes;
        $this->isActive = $s->is_active;
        $this->editing = true;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'ruc' => $this->ruc,
            'phone' => $this->phone,
            'email' => $this->email,
            'contact_person' => $this->contactPerson,
            'payment_terms' => $this->paymentTerms,
            'notes' => $this->notes,
            'is_active' => $this->isActive,
        ];
        if ($this->editing) {
            Supplier::findOrFail($this->supplierId)->update($data);
        } else {
            Supplier::create($data);
        }
        $this->showForm = false;
        $this->resetForm();
    }

    public function delete($id): void
    {
        Supplier::findOrFail($id)->delete();
    }

    public function toggleActive($id): void
    {
        $s = Supplier::findOrFail($id);
        $s->update(['is_active' => !$s->is_active]);
    }

    private function resetForm(): void
    {
        $this->supplierId = null;
        $this->name = '';
        $this->ruc = '';
        $this->phone = '';
        $this->email = '';
        $this->contactPerson = '';
        $this->paymentTerms = '';
        $this->notes = '';
        $this->isActive = true;
    }

    public function render()
    {
        return view('livewire.inventory.supplier-index')
            ->layout('layouts.app');
    }
}
