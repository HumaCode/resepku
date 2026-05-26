<x-mail::message>
<div style="text-align: center; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
<div style="text-align: center; margin-top: 10px; margin-bottom: 25px;">
<div style="display: inline-block; width: 64px; height: 64px; background-color: #fdf2eb; border-radius: 50%; line-height: 64px; font-size: 30px; text-align: center; margin: 0 auto; box-shadow: 0 8px 24px rgba(232, 93, 38, 0.12);">🔑</div>
</div>

@if (! empty($greeting))
<h1 style="color: #2d1b0e; font-size: 22px; font-weight: 700; margin-top: 0; text-align: center; font-family: 'Poppins', sans-serif;">{{ $greeting }}</h1>
@else
@if ($level === 'error')
<h1 style="color: #dc2626; font-size: 22px; font-weight: 700; margin-top: 0; text-align: center; font-family: 'Poppins', sans-serif;">@lang('Whoops!')</h1>
@else
<h1 style="color: #2d1b0e; font-size: 22px; font-weight: 700; margin-top: 0; text-align: center; font-family: 'Poppins', sans-serif;">@lang('Hello!')</h1>
@endif
@endif

@foreach ($introLines as $line)
<p style="font-size: 15px; line-height: 1.6; color: #6e5d53; text-align: center; margin: 15px 0; font-family: 'Poppins', sans-serif;">{{ $line }}</p>
@endforeach

@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<div style="margin: 30px auto; text-align: center;">
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
</div>
@endisset

@foreach ($outroLines as $line)
<p style="font-size: 15px; line-height: 1.6; color: #6e5d53; text-align: center; margin: 15px 0; font-family: 'Poppins', sans-serif;">{{ $line }}</p>
@endforeach
</div>

@isset($actionText)
<x-slot:subcopy>
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    "into your web browser:",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
