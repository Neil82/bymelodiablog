<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-brand-gradient-alt border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wide hover:opacity-90 focus:opacity-90 active:opacity-80 focus:outline-none focus:ring-2 focus:ring-azul-claro focus:ring-offset-2 transition-all ease-in-out duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl']) }}>
    {{ $slot }}
</button>
