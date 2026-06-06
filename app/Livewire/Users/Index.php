<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Usuarios')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $showForm = false;
    public $editing = false;
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $pin = '';
    public $is_active = true;
    public $selectedRoles = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $this->userId,
            'pin' => 'nullable|digits:4',
            'is_active' => 'boolean',
            'selectedRoles' => 'required|array|min:1',
        ];

        if (!$this->editing || ($this->editing && !empty($this->password))) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editing = false;
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->pin = $user->pin ?? '';
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();
        $this->showForm = true;
        $this->editing = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editing) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            if ($this->pin) {
                $user->update(['pin' => $this->pin]);
            }

            if (!empty($this->password)) {
                $user->update(['password' => bcrypt($this->password)]);
            }
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'pin' => $this->pin ?: null,
                'is_active' => $this->is_active,
            ]);
        }

        $user->syncRoles($this->selectedRoles);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => $this->editing ? 'Usuario actualizado' : 'Usuario creado',
            'text' => $this->editing ? 'Los cambios se guardaron correctamente.' : 'El nuevo usuario fue registrado exitosamente.',
            'timer' => 3000,
        ]);

        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Estado actualizado',
            'text' => $user->is_active ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.',
            'timer' => 2000,
        ]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Operación no permitida',
                'text' => 'No puedes eliminarte a ti mismo.',
            ]);
            return;
        }
        $user->delete();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario fue eliminado correctamente.',
            'timer' => 2000,
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirm', [
            'title' => '¿Eliminar usuario?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmText' => 'Sí, eliminar',
            'cancelText' => 'Cancelar',
            'callback' => 'delete',
            'params' => ['id' => $id],
        ]);
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editing = false;
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->passwordConfirmation = '';
        $this->pin = '';
        $this->is_active = true;
        $this->selectedRoles = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::query()
                ->when($this->search, fn($q) => $q->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                }))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'roles' => Role::all(),
        ]);
    }
}
