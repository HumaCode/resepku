@props([
    'id' => null,
    'select2' => false,
])

<select id="{{ $id }}"
        {{ $attributes->class(['form-input-m', 'select2-enabled' => $select2]) }}
        @if($select2) data-select-two="true" @endif>
    {{ $slot }}
</select>
