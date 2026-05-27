@props([
    'required' => false
])

<label {{ $attributes->merge(['class' => 'form-label-m']) }}>
    {{ $slot }}
    @if($required)
        <span class="req">*</span>
    @endif
</label>
