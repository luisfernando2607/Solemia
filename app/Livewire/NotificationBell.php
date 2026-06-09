<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $showDropdown = false;

    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function getUnreadCountProperty()
    {
        return Notification::where('user_id', Auth::id())->where('is_read', false)->count();
    }

    public function getNotificationsProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(15)
            ->get();
    }

    public function markRead($id): void
    {
        Notification::where('id', $id)->where('user_id', Auth::id())
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markAllRead(): void
    {
        Notification::where('user_id', Auth::id())->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function toggle(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
