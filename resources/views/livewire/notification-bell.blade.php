<div class="relative" x-data="{ open: @entangle('showDropdown') }" @click.outside="open = false">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-olive-600 transition-colors" title="Notificaciones">
        <i class="fas fa-bell text-base md:text-lg"></i>
        @if($this->unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 flex items-center justify-center bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[18px] min-h-[18px]">
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak
        class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 max-h-96 flex flex-col">
        <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100">
            <h3 class="text-xs font-semibold text-gray-900 uppercase tracking-wider">Notificaciones</h3>
            @if($this->unreadCount > 0)
                <button wire:click="markAllRead" class="text-[10px] text-olive-600 hover:text-olive-800 font-medium">
                    Marcar todas leídas
                </button>
            @endif
        </div>
        <div class="flex-1 overflow-y-auto divide-y divide-gray-50 max-h-72">
            @forelse($this->notifications as $n)
                <div class="px-4 py-2.5 hover:bg-gray-50 transition-colors {{ !$n->is_read ? 'bg-olive-50/50' : '' }}"
                    wire:click="markRead({{ $n->id }})" style="cursor:pointer">
                    <div class="flex items-start gap-2.5">
                        <div class="mt-0.5 shrink-0">
                            @php
                                $icon = match($n->type) {
                                    'stock_alert' => 'fa-exclamation-triangle text-amber-500',
                                    'order_ready' => 'fa-utensils text-emerald-500',
                                    'cash_closure' => 'fa-cash-register text-blue-500',
                                    'sri_error' => 'fa-file-invoice text-red-500',
                                    'user_created' => 'fa-user-plus text-purple-500',
                                    default => 'fa-bell text-gray-400',
                                };
                            @endphp
                            <i class="fas {{ $icon }} text-xs w-4 text-center"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-900 truncate">{{ $n->title }}</p>
                            @if($n->message)
                                <p class="text-[10px] text-gray-500 mt-0.5 line-clamp-2">{{ $n->message }}</p>
                            @endif
                            <p class="text-[9px] text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$n->is_read)
                            <span class="w-1.5 h-1.5 rounded-full bg-olive-600 shrink-0 mt-1.5"></span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                    <i class="fas fa-bell-slash text-xl mb-2"></i>
                    <p class="text-xs">Sin notificaciones</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
