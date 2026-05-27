@props([
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'value' => '',
])

<input type="{{ $type }}" 
       id="{{ $id }}" 
       placeholder="{{ $placeholder }}" 
       value="{{ $value }}"
       {{ $attributes->merge(['class' => 'form-input-m']) }} />
