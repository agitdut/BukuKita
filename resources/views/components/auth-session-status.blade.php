@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#1b4332]']) }}>
        {{ $status }}
    </div>
@endif
