<?php

namespace App\Livewire\Pos;

use App\Models\Order;
use App\Models\TableModel;
use App\Models\Zone;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('POS / Sala')]
class Tables extends Component
{
    public $selectedZone = null;
    public $showZoneForm = false;
    public $showTableForm = false;
    public $editingZone = false;
    public $editingTable = false;
    public $zoneId = null;
    public $tableId = null;

    // Zone fields
    public $zoneName = '';
    public $zoneDescription = '';
    public $zoneSortOrder = 0;

    // Table fields
    public $tableZoneId = '';
    public $tableNumber = '';
    public $tableCapacity = 4;
    public $tableShape = 'square';

    public $search = '';

    protected function rules()
    {
        return [
            'zoneName' => 'required|string|max:80',
            'zoneDescription' => 'nullable|string|max:255',
            'zoneSortOrder' => 'integer|min:0',
        ];
    }

    protected function tableRules()
    {
        $unique = $this->editingTable
            ? "unique:tables,number,{$this->tableId},id,zone_id,{$this->tableZoneId}"
            : "unique:tables,number,NULL,id,zone_id,{$this->tableZoneId}";
        return [
            'tableZoneId' => 'required|exists:zones,id',
            'tableNumber' => "required|string|max:10|{$unique}",
            'tableCapacity' => 'required|integer|min:1|max:20',
            'tableShape' => 'required|in:square,round,rectangle',
        ];
    }

    public function selectZone($zoneId)
    {
        $this->selectedZone = $zoneId;
        $this->showZoneForm = false;
        $this->showTableForm = false;
    }

    // Zone CRUD
    public function createZone()
    {
        $this->resetZoneForm();
        $this->showZoneForm = true;
        $this->editingZone = false;
        $this->showTableForm = false;
    }

    public function editZone($id)
    {
        $zone = Zone::findOrFail($id);
        $this->zoneId = $zone->id;
        $this->zoneName = $zone->name;
        $this->zoneDescription = $zone->description ?? '';
        $this->zoneSortOrder = $zone->sort_order;
        $this->showZoneForm = true;
        $this->editingZone = true;
        $this->showTableForm = false;
    }

    public function saveZone()
    {
        $this->validate();

        if ($this->editingZone) {
            Zone::findOrFail($this->zoneId)->update([
                'name' => $this->zoneName,
                'description' => $this->zoneDescription,
                'sort_order' => $this->zoneSortOrder,
            ]);
        } else {
            Zone::create([
                'name' => $this->zoneName,
                'description' => $this->zoneDescription,
                'sort_order' => $this->zoneSortOrder,
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success', 'title' => 'Zona guardada',
            'text' => 'La zona se ha guardado correctamente.', 'timer' => 2000,
        ]);
        $this->resetZoneForm();
    }

    public function deleteZone($id)
    {
        $zone = Zone::withCount('tables')->findOrFail($id);
        if ($zone->tables_count > 0) {
            $this->dispatch('swal', [
                'icon' => 'error', 'title' => 'No se puede eliminar',
                'text' => 'Esta zona tiene mesas asignadas. Elimina primero las mesas.',
            ]);
            return;
        }
        $zone->delete();
        $this->dispatch('swal', [
            'icon' => 'success', 'title' => 'Zona eliminada', 'timer' => 2000,
        ]);
    }

    // Table CRUD
    public function createTable()
    {
        $this->resetTableForm();
        $this->tableZoneId = $this->selectedZone ?? '';
        $this->showTableForm = true;
        $this->editingTable = false;
        $this->showZoneForm = false;
    }

    public function editTable($id)
    {
        $table = TableModel::findOrFail($id);
        $this->tableId = $table->id;
        $this->tableZoneId = $table->zone_id;
        $this->tableNumber = $table->number;
        $this->tableCapacity = $table->capacity;
        $this->tableShape = $table->shape;
        $this->showTableForm = true;
        $this->editingTable = true;
        $this->showZoneForm = false;
    }

    public function saveTable()
    {
        $this->validate($this->tableRules());

        if ($this->editingTable) {
            TableModel::findOrFail($this->tableId)->update([
                'zone_id' => $this->tableZoneId,
                'number' => $this->tableNumber,
                'capacity' => $this->tableCapacity,
                'shape' => $this->tableShape,
            ]);
        } else {
            TableModel::create([
                'zone_id' => $this->tableZoneId,
                'number' => $this->tableNumber,
                'capacity' => $this->tableCapacity,
                'shape' => $this->tableShape,
                'status' => 'available',
            ]);
        }

        $this->dispatch('swal', [
            'icon' => 'success', 'title' => 'Mesa guardada',
            'text' => 'La mesa se ha guardado correctamente.', 'timer' => 2000,
        ]);
        $this->resetTableForm();
    }

    public function deleteTable($id)
    {
        $table = TableModel::findOrFail($id);
        if ($table->activeOrder()->exists()) {
            $this->dispatch('swal', [
                'icon' => 'error', 'title' => 'Mesa ocupada',
                'text' => 'No puedes eliminar una mesa con una comanda activa.',
            ]);
            return;
        }
        $table->delete();
        $this->dispatch('swal', [
            'icon' => 'success', 'title' => 'Mesa eliminada', 'timer' => 2000,
        ]);
    }

    public function changeTableStatus($id, $status)
    {
        $table = TableModel::findOrFail($id);
        $table->update(['status' => $status]);
        $this->dispatch('swal', [
            'icon' => 'success', 'title' => 'Estado actualizado', 'timer' => 1500,
        ]);
    }

    // Helpers
    public function resetZoneForm()
    {
        $this->showZoneForm = false;
        $this->editingZone = false;
        $this->zoneId = null;
        $this->zoneName = '';
        $this->zoneDescription = '';
        $this->zoneSortOrder = 0;
        $this->resetErrorBag();
    }

    public function resetTableForm()
    {
        $this->showTableForm = false;
        $this->editingTable = false;
        $this->tableId = null;
        $this->tableNumber = '';
        $this->tableCapacity = 4;
        $this->tableShape = 'square';
        $this->tableZoneId = '';
        $this->resetErrorBag();
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'available' => 'bg-emerald-500',
            'occupied' => 'bg-red-500',
            'reserved' => 'bg-gold-500',
            'billing' => 'bg-blue-500',
            'blocked' => 'bg-gray-400',
            default => 'bg-gray-200',
        };
    }

    public function getStatusLabel($status)
    {
        return match ($status) {
            'available' => 'Disponible',
            'occupied' => 'Ocupada',
            'reserved' => 'Reservada',
            'billing' => 'En cuenta',
            'blocked' => 'Bloqueada',
            default => $status,
        };
    }

    public function render()
    {
        $zones = Zone::with(['tables' => function ($q) {
            $q->with('activeOrder')->orderBy('number');
        }])->where('is_active', true)->orderBy('sort_order')->get();

        $selectedZoneData = null;
        if ($this->selectedZone) {
            $selectedZoneData = $zones->firstWhere('id', $this->selectedZone);
        } elseif ($zones->isNotEmpty()) {
            $selectedZoneData = $zones->first();
            $this->selectedZone = $selectedZoneData->id;
        }

        return view('livewire.pos.tables', [
            'zones' => $zones,
            'selectedZoneData' => $selectedZoneData,
        ]);
    }
}
