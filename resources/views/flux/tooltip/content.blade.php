@props([
    'kbd' => null,
])

@php
    $classes = Flux::classes([
        'relative py-2 px-2.5',
        'rounded-md',
        'text-xs font-medium',
        'text-neutral-900 dark:text-white',
        'bg-white dark:bg-neutral-800 shadow-md dark:shadow-none border border-neutral-200 dark:border-white/10',
        'p-0 overflow-visible',
    ]);
@endphp

<div popover="manual" {{ $attributes->class($classes) }} data-flux-tooltip-content>
    {{ $slot }}

    <?php if ($kbd): ?>
    <span class="ps-1 text-zinc-300">{{ $kbd }}</span>
    <?php endif; ?>
</div>
