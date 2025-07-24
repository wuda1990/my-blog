<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# 哎呀！
@else
# 您好！
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
此致敬礼，<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
如果您在点击"{{ $actionText }}"按钮时遇到问题，请复制以下链接并粘贴到您的浏览器中： <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
