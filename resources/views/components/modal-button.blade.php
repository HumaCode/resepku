@props([
    'variant' => 'primary', // primary, cancel, danger
    'type' => 'button'
])

@php
    $class = match($variant) {
        'primary' => 'btn-modal-primary',
        'cancel' => 'btn-modal-cancel',
        'danger' => 'btn-del-ok',
        default => 'btn-modal-primary'
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</button>
