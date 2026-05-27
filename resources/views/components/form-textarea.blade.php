@props([
    'id' => null,
    'placeholder' => '',
    'rows' => 3
])

<textarea id="{{ $id }}" 
          placeholder="{{ $placeholder }}" 
          rows="{{ $rows }}"
          {{ $attributes->merge(['class' => 'form-input-m']) }}>{{ $slot }}</textarea>
