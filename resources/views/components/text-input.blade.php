@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#c9a227] focus:ring-[#c9a227] rounded-md shadow-sm']) }}>
