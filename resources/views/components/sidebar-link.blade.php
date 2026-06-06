@props(['active' => false, 'href' => '#', 'icon' => 'fas fa-circle'])

@php
$classes = $active
    ? 'sidebar-link sidebar-link-active'
    : 'sidebar-link sidebar-link-inactive';
@endphp

<a href="{{ $href }}" wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} w-5 text-center text-sm md:text-base"></i>
    <span class="text-sm md:text-base">{{ $slot }}</span>
</a>
