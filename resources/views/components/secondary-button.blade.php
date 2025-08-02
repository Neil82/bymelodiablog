<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-azul-claro/30 dark:border-azul-claro/50 rounded-xl font-semibold text-sm text-azul-intenso dark:text-azul-claro uppercase tracking-wide shadow-lg hover:bg-azul-claro/10 dark:hover:bg-azul-claro/20 hover:border-azul-claro focus:outline-none focus:ring-2 focus:ring-azul-claro focus:ring-offset-2 disabled:opacity-25 transition-all ease-in-out duration-300 transform hover:scale-105']) }}>
    {{ $slot }}
</button>
