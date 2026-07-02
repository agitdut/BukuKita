<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#1b4332] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#0d2818] focus:bg-[#0d2818] active:bg-[#0d2818] focus:outline-none focus:ring-2 focus:ring-[#c9a227] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
