<?php

namespace App\Livewire\Settings;

use App\Models\Printer;
use App\Models\RestaurantSetting;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    public $activeTab = 'restaurant';

    // ===== Restaurant Settings =====
    public $tradeName = '';
    public $legalName = '';
    public $ruc = '';
    public $address = '';
    public $phone = '';
    public $email = '';
    public $currency = 'USD';
    public $timezone = 'America/Guayaquil';
    public $dateFormat = 'Y-m-d';
    public $decimalSep = '.';
    public $sriEnvironment = 'test';
    public $sriTaxpayerType = 'otro';
    public $sriCertPath = '';
    public $sriCertPass = '';
    public $kdsAlertMin = 10;

    // ===== Taxes & Tips =====
    public $taxRate = 15;
    public $serviceChargeActive = false;
    public $serviceChargeRate = 0;
    public $tipSug10 = 10;
    public $tipSug15 = 15;
    public $tipSug20 = 20;

    // ===== Shifts =====
    public $showShiftForm = false;
    public $editingShift = false;
    public $shiftId = null;
    public $shiftName = '';
    public $shiftStart = '';
    public $shiftEnd = '';
    public $shiftActive = true;
    public $shiftUsers = [];

    // ===== Printers =====
    public $showPrinterForm = false;
    public $editingPrinter = false;
    public $printerId = null;
    public $printerName = '';
    public $printerType = 'thermal_escpos';
    public $printerIp = '';
    public $printerPort = 9100;
    public $printerModel = '';
    public $printerFunction = 'ticket_client';
    public $printerArea = '';
    public $printerActive = true;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount(): void
    {
        $settings = RestaurantSetting::first();
        if ($settings) {
            $this->tradeName = $settings->trade_name;
            $this->legalName = $settings->legal_name ?? '';
            $this->ruc = $settings->ruc ?? '';
            $this->address = $settings->address ?? '';
            $this->phone = $settings->phone ?? '';
            $this->email = $settings->email ?? '';
            $this->currency = $settings->currency;
            $this->timezone = $settings->timezone;
            $this->dateFormat = $settings->date_format;
            $this->decimalSep = $settings->decimal_separator;
            $this->sriEnvironment = $settings->sri_environment;
            $this->sriTaxpayerType = $settings->sri_taxpayer_type ?? 'otro';
            $this->sriCertPath = $settings->sri_certificate_path ?? '';
            $this->sriCertPass = $settings->sri_certificate_pass ?? '';
            $this->kdsAlertMin = $settings->kds_alert_minutes ?? 10;
            $this->taxRate = (float)$settings->tax_rate;
            $this->serviceChargeActive = $settings->service_charge_active;
            $this->serviceChargeRate = (float)$settings->service_charge_rate;
            $tips = $settings->tip_suggestions ?? [10, 15, 20];
            $this->tipSug10 = $tips[0] ?? 10;
            $this->tipSug15 = $tips[1] ?? 15;
            $this->tipSug20 = $tips[2] ?? 20;
        }
    }

    public function changeTab($tab): void
    {
        $this->activeTab = $tab;
    }

    // ===== Save Restaurant =====
    public function saveRestaurant(): void
    {
        $this->validate([
            'tradeName' => 'required|string|max:150',
            'ruc' => 'nullable|string|max:13',
        ]);

        $settings = RestaurantSetting::firstOrNew();
        $settings->fill([
            'trade_name' => $this->tradeName,
            'legal_name' => $this->legalName,
            'ruc' => $this->ruc,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'currency' => $this->currency,
            'timezone' => $this->timezone,
            'date_format' => $this->dateFormat,
            'decimal_separator' => $this->decimalSep,
            'sri_environment' => $this->sriEnvironment,
            'sri_taxpayer_type' => $this->sriTaxpayerType,
            'sri_certificate_path' => $this->sriCertPath,
            'sri_certificate_pass' => $this->sriCertPass,
            'kds_alert_minutes' => (int)$this->kdsAlertMin,
        ]);
        $settings->save();
        RestaurantSetting::flushCache();
        $this->js("Swal.fire({icon:'success',title:'Configuración guardada',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    // ===== Save Taxes =====
    public function saveTaxes(): void
    {
        $this->validate([
            'taxRate' => 'required|numeric|min:0|max:100',
            'serviceChargeRate' => 'required|numeric|min:0|max:100',
        ]);

        $settings = RestaurantSetting::firstOrNew();
        $settings->fill([
            'tax_rate' => $this->taxRate,
            'service_charge_active' => $this->serviceChargeActive,
            'service_charge_rate' => $this->serviceChargeRate,
            'tip_suggestions' => [(int)$this->tipSug10, (int)$this->tipSug15, (int)$this->tipSug20],
        ]);
        $settings->save();
        RestaurantSetting::flushCache();
        $this->js("Swal.fire({icon:'success',title:'Impuestos guardados',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    // ===== Shifts CRUD =====
    public function getShiftsProperty()
    {
        return Shift::withCount('users')->orderBy('start_time')->get();
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function createShift(): void
    {
        $this->resetShiftForm();
        $this->showShiftForm = true;
        $this->editingShift = false;
    }

    public function editShift($id): void
    {
        $s = Shift::with('users')->findOrFail($id);
        $this->shiftId = $s->id;
        $this->shiftName = $s->name;
        $this->shiftStart = substr($s->start_time, 0, 5);
        $this->shiftEnd = substr($s->end_time, 0, 5);
        $this->shiftActive = $s->is_active;
        $this->shiftUsers = $s->users->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->editingShift = true;
        $this->showShiftForm = true;
    }

    public function saveShift(): void
    {
        $this->validate([
            'shiftName' => 'required|string|max:80',
            'shiftStart' => 'required',
            'shiftEnd' => 'required',
        ]);

        $data = [
            'name' => $this->shiftName,
            'start_time' => $this->shiftStart,
            'end_time' => $this->shiftEnd,
            'is_active' => $this->shiftActive,
        ];

        if ($this->editingShift) {
            $shift = Shift::findOrFail($this->shiftId);
            $shift->update($data);
        } else {
            $shift = Shift::create($data);
        }

        $isEdit = $this->editingShift;
        $shift->users()->sync($this->shiftUsers);
        $this->showShiftForm = false;
        $this->resetShiftForm();
        $this->js("Swal.fire({icon:'success',title:'Turno " . ($isEdit ? 'actualizado' : 'creado') . "',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    public function deleteShift($id): void
    {
        Shift::findOrFail($id)->delete();
        $this->js("Swal.fire({icon:'success',title:'Turno eliminado',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    private function resetShiftForm(): void
    {
        $this->shiftId = null;
        $this->shiftName = '';
        $this->shiftStart = '';
        $this->shiftEnd = '';
        $this->shiftActive = true;
        $this->shiftUsers = [];
    }

    // ===== Printers CRUD =====
    public function getPrintersProperty()
    {
        return Printer::orderBy('name')->get();
    }

    public function createPrinter(): void
    {
        $this->resetPrinterForm();
        $this->showPrinterForm = true;
        $this->editingPrinter = false;
    }

    public function editPrinter($id): void
    {
        $p = Printer::findOrFail($id);
        $this->printerId = $p->id;
        $this->printerName = $p->name;
        $this->printerType = $p->type;
        $this->printerIp = $p->ip_address;
        $this->printerPort = $p->port;
        $this->printerModel = $p->model ?? '';
        $this->printerFunction = $p->printer_function;
        $this->printerArea = $p->kitchen_area ?? '';
        $this->printerActive = $p->is_active;
        $this->editingPrinter = true;
        $this->showPrinterForm = true;
    }

    public function savePrinter(): void
    {
        $this->validate([
            'printerName' => 'required|string|max:80',
            'printerIp' => 'required|string|max:45',
            'printerPort' => 'required|numeric|min:1|max:65535',
        ]);

        $data = [
            'name' => $this->printerName,
            'type' => $this->printerType,
            'ip_address' => $this->printerIp,
            'port' => (int)$this->printerPort,
            'model' => $this->printerModel,
            'printer_function' => $this->printerFunction,
            'kitchen_area' => $this->printerArea,
            'is_active' => $this->printerActive,
        ];

        $isEdit = $this->editingPrinter;
        if ($isEdit) {
            Printer::findOrFail($this->printerId)->update($data);
        } else {
            Printer::create($data);
        }
        $this->showPrinterForm = false;
        $this->resetPrinterForm();
        $this->js("Swal.fire({icon:'success',title:'Impresora " . ($isEdit ? 'actualizada' : 'creada') . "',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    public function deletePrinter($id): void
    {
        Printer::findOrFail($id)->delete();
        $this->js("Swal.fire({icon:'success',title:'Impresora eliminada',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    public function togglePrinter($id): void
    {
        $p = Printer::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
        $status = $p->fresh()->is_active ? 'activada' : 'desactivada';
        $this->js("Swal.fire({icon:'success',title:'Impresora {$status}',toast:true,position:'top-end',showConfirmButton:false,timer:2000,timerProgressBar:true})");
    }

    private function resetPrinterForm(): void
    {
        $this->printerId = null;
        $this->printerName = '';
        $this->printerType = 'thermal_escpos';
        $this->printerIp = '';
        $this->printerPort = 9100;
        $this->printerModel = '';
        $this->printerFunction = 'ticket_client';
        $this->printerArea = '';
        $this->printerActive = true;
    }

    public function render()
    {
        return view('livewire.settings.index')
            ->layout('layouts.app');
    }
}
