<span wire:poll.5s class="absolute z-10"
      x-bind:class="sidebarCollapsed ? '-top-1 right-0' : 'right-2 inset-y-0 my-auto h-fit'">
    @if($this->count > 0)
    <span class="inline-flex items-center justify-center bg-gold-500 text-white text-[9px] font-bold rounded-full min-w-[18px] h-[18px] px-1">
        {{ $this->count > 99 ? '99+' : $this->count }}
    </span>
    @endif
</span>
