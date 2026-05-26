@props([
    'id',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'icon' => '',
    'value' => '',
    'autocomplete' => null,
    'required' => false,
    'autofocus' => false,
    'togglePassword' => false
])

<div class="mb-3 text-start">
    <div class="input-wrap">
        @if($icon)
            <i class="bi {{ $icon }} icon-left"></i>
        @endif
        
        <input 
            type="{{ $type }}" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            value="{{ $value }}" 
            placeholder="{{ $placeholder }}" 
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            {{ $attributes->merge(['class' => '']) }}
        />
        
        @if($togglePassword)
            <button type="button" class="toggle-pw" id="togglePw">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        @endif
    </div>
    <div class="error-feedback text-danger small mt-1" id="error-{{ $name }}">
        @error($name) {{ $message }} @enderror
    </div>
</div>
