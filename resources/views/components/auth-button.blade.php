@props([
    'id' => 'loginBtn',
    'type' => 'submit',
    'text' => 'Masuk Sekarang'
])

<button type="{{ $type }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'btn-login']) }}>
    <div class="spinner"></div>
    <span class="btn-text">{{ $text }}</span>
</button>
