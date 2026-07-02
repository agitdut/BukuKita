@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#1b4332]']) }}>
    {{ $value ?? $slot }}
</label>
