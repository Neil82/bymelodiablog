@props(['current' => ''])

<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-3">
                    <img 
                        id="admin-logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-8 w-auto transition-all duration-300"
                    >
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Admin
                    </h1>
                </div>
                <nav class="flex space-x-4">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="px-3 py-2 text-sm font-medium @if($current === 'posts') text-white bg-blue-600 rounded-lg @else text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white @endif">
                        Posts
                    </a>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-3 py-2 text-sm font-medium @if($current === 'categories') text-white bg-blue-600 rounded-lg @else text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white @endif">
                        Categorías
                    </a>
                    <a href="{{ route('admin.comments.index') }}" 
                       class="px-3 py-2 text-sm font-medium @if($current === 'comments') text-white bg-blue-600 rounded-lg @else text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white @endif">
                        Comentarios
                        @if($pendingCommentsCount > 0)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $pendingCommentsCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.featured-posts.index') }}" 
                       class="px-3 py-2 text-sm font-medium @if($current === 'featured-posts') text-white bg-blue-600 rounded-lg @else text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white @endif">
                        Posts Destacados
                    </a>
                    <a href="{{ route('admin.settings.index') }}" 
                       class="px-3 py-2 text-sm font-medium @if($current === 'settings') text-white bg-blue-600 rounded-lg @else text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white @endif">
                        Configuración
                    </a>
                </nav>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle -->
                <button
                    id="theme-toggle"
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    aria-label="Toggle theme"
                >
                    <span id="theme-icon">🌙</span>
                </button>
                
                <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    Ver sitio
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const adminLogo = document.getElementById('admin-logo');
    const html = document.documentElement;
    
    // Get current theme from localStorage or default to light
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply current theme
    html.classList.remove('light', 'dark');
    html.classList.add(currentTheme);
    
    // Update elements based on current theme
    updateThemeElements(currentTheme);
    
    // Theme toggle event listener
    themeToggle.addEventListener('click', function() {
        const isDark = html.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        
        // Update HTML class
        html.classList.remove('light', 'dark');
        html.classList.add(newTheme);
        
        // Save to localStorage
        localStorage.setItem('theme', newTheme);
        
        // Update elements
        updateThemeElements(newTheme);
    });
    
    function updateThemeElements(theme) {
        // Update theme icon
        themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
        
        // Update admin logo
        if (adminLogo) {
            if (theme === 'dark') {
                adminLogo.src = '/images/bymelodia_blanco.png';
            } else {
                adminLogo.src = '/images/bymelodia_negro.png';
            }
        }
    }
});
</script>