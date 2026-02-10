<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-red-600 hover:to-red-700 active:from-red-700 active:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
