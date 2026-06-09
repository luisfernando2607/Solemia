@props(['active' => false, 'href' => '#', 'icon' => 'fas fa-circle', 'collapsed' => false])

@php
$classes = $active
    ? 'sidebar-link sidebar-link-active'
    : 'sidebar-link sidebar-link-inactive';
@endphp

<a href="{{ $href }}" wire:navigate {{ $attributes->merge(['class' => $classes]) }}
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   :title="sidebarCollapsed ? '{{ $slot }}' : ''">
    <i class="{{ $icon }} w-5 text-center text-sm md:text-base"></i>
    <span class="text-sm md:text-base" x-show="!sidebarCollapsed">{{ $slot }}</span>
</a>
