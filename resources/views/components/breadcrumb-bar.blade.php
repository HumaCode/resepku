@props([
    'title',
    'icon' => 'bi-grid-1x2',
    'desc' => null,
    'items' => []
])

<div class="breadcrumb-bar" data-aos="fade-down" data-aos-duration="600">
    <div class="bc-left">
        <div class="bc-title">
            <i class="bi {{ $icon }}"></i>
            {{ $title }}
        </div>
        @if($desc)
            <div class="bc-desc">{{ $desc }}</div>
        @endif
    </div>
    <div class="bc-right">
        <i class="bi bi-house-fill text-warning"></i>
        @foreach($items as $label => $link)
            @if($loop->first && $label !== 'Home')
                <a href="{{ route('dashboard') }}">Home</a>
                <i class="bi bi-chevron-right bc-sep"></i>
            @endif
            
            @if($link)
                <a href="{{ $link }}">{{ $label }}</a>
                <i class="bi bi-chevron-right bc-sep"></i>
            @else
                <span style="color:var(--primary); font-weight:600">{{ $label }}</span>
            @endif
        @endforeach
    </div>
</div>
