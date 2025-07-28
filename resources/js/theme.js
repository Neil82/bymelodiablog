// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const logo = document.getElementById('logo');
    const html = document.documentElement;
    
    // Get current theme from the HTML class
    const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
    
    // Update elements based on current theme
    updateThemeElements(currentTheme);
    
    // Theme toggle handler
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                // Switch to light mode
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
                updateThemeElements('light');
            } else {
                // Switch to dark mode
                html.classList.add('dark');
                html.classList.remove('light');
                localStorage.setItem('theme', 'dark');
                updateThemeElements('dark');
            }
        });
    }
    
    // Update theme elements
    function updateThemeElements(theme) {
        // Update icon
        if (themeIcon) {
            themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
        }
        
        // Update logo
        if (logo) {
            if (theme === 'dark') {
                logo.src = '/images/bymelodia_blanco.png';
            } else {
                logo.src = '/images/bymelodia_negro.png';
            }
        }
    }
});