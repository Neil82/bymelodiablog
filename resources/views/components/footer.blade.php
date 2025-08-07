<!-- Footer -->
<footer class="bg-gradient-to-b from-azul-claro/10 to-rosado-pastel/10 dark:from-azul-intenso/20 dark:to-azul-claro/20 border-t border-azul-claro/20 mt-16">
    <!-- Premium Content Subscription Section -->
    <div class="bg-gradient-to-br from-green-50 via-white to-yellow-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 relative overflow-hidden">
        <!-- Decorative flower elements -->
        <div class="absolute top-8 left-8 opacity-20">
            <svg width="120" height="120" viewBox="0 0 120 120" class="text-green-300">
                <path d="M60 20C70 30 80 40 90 50C80 60 70 70 60 80C50 70 40 60 30 50C40 40 50 30 60 20Z" fill="currentColor"/>
            </svg>
        </div>
        <div class="absolute bottom-8 right-8 opacity-20">
            <svg width="80" height="80" viewBox="0 0 80 80" class="text-yellow-300">
                <path d="M40 10C50 20 60 30 70 40C60 50 50 60 40 70C30 60 20 50 10 40C20 30 30 20 40 10Z" fill="currentColor"/>
            </svg>
        </div>
        
        <div class="max-w-screen-xl mx-auto py-16 px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Content -->
                <div class="text-left space-y-6">
                    <div class="space-y-4">
                        <h2 class="text-4xl md:text-5xl font-bold text-green-600 leading-tight font-libre">
                            Contenido premium
                        </h2>
                        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight font-libre">
                            sólo para chicas igual de ambiciosas que yo
                        </h3>
                    </div>
                    
                    <div class="space-y-4 text-gray-700 dark:text-gray-300 text-lg">
                        <p class="leading-relaxed">
                            Recibe en tu correo plantillas exclusivas, tips que no publico en redes y rutinas que harán tu vida más ligera y bonita.
                        </p>
                        <p class="leading-relaxed">
                            Nada de spam, solo cosas de valor para ti y tu crecimiento.
                        </p>
                    </div>
                </div>
                
                <!-- Right Side - Form -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-700">
                    <form id="premium-newsletter-form" class="space-y-6">
                        @csrf
                        <div>
                            <input type="text" 
                                   name="name" 
                                   placeholder="Nombre" 
                                   required
                                   class="w-full px-8 py-5 rounded-full border-2 border-green-200 dark:border-green-300 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:border-green-400 focus:ring-0 transition-colors text-lg outline-none">
                        </div>
                        
                        <div>
                            <input type="email" 
                                   name="email" 
                                   placeholder="El correo que usas mas" 
                                   required
                                   class="w-full px-8 py-5 rounded-full border-2 border-green-200 dark:border-green-300 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:border-green-400 focus:ring-0 transition-colors text-lg outline-none">
                        </div>
                        
                        <button type="submit" 
                                class="w-full px-8 py-5 bg-gradient-to-r from-green-400 to-green-500 hover:from-green-500 hover:to-green-600 text-white font-semibold text-lg rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border-0 outline-none">
                            Unirme ahora
                        </button>
                    </form>
                    <div id="premium-newsletter-message" class="mt-4 text-sm hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About -->
            <div class="text-center md:text-left">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.footer.about') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ \App\Models\SiteSetting::get('site_description', 'Blog sobre música y cultura en español') }}
                </p>
            </div>
            
            <!-- Quick Links -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.footer.quick_links') }}</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro text-sm transition-colors">
                            {{ __('ui.nav.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro text-sm transition-colors">
                            {{ __('ui.nav.blog') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy.policy') }}" class="text-gray-600 dark:text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro text-sm transition-colors">
                            {{ __('ui.footer.privacy_policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms.service') }}" class="text-gray-600 dark:text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro text-sm transition-colors">
                            {{ __('ui.footer.terms_of_service') }}
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Social & Contact -->
            <div class="text-center md:text-right">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.footer.connect') }}</h3>
                <div class="flex justify-center md:justify-end space-x-4 mb-4">
                    @if($facebook = \App\Models\SiteSetting::get('social_facebook'))
                        <a href="{{ $facebook }}" target="_blank" class="text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro transition-all duration-300 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                    @if($twitter = \App\Models\SiteSetting::get('social_twitter'))
                        <a href="{{ $twitter }}" target="_blank" class="text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro transition-all duration-300 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    @endif
                    @if($instagram = \App\Models\SiteSetting::get('social_instagram'))
                        <a href="{{ $instagram }}" target="_blank" class="text-gray-400 hover:text-azul-intenso dark:hover:text-azul-claro transition-all duration-300 transform hover:scale-110">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                            </svg>
                        </a>
                    @endif
                </div>
                @if($contact_email = \App\Models\SiteSetting::get('contact_email'))
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('ui.footer.contact') }}: 
                        <a href="mailto:{{ $contact_email }}" class="hover:text-azul-intenso dark:hover:text-azul-claro transition-colors">
                            {{ $contact_email }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Copyright & Credits -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    © {{ date('Y') }} {{ \App\Models\SiteSetting::get('site_name', 'ByMelodia') }}. {{ __('ui.footer.all_rights_reserved') }}.
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 md:mt-0">
                    {{ __('ui.footer.powered_by') }} 
                    <a href="https://adratech.click" target="_blank" rel="noopener" class="text-azul-intenso dark:text-azul-claro hover:text-rosado-pastel dark:hover:text-rosado-pastel font-semibold transition-colors">
                        Adratech Systems
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Premium Newsletter JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('premium-newsletter-form');
    const messageDiv = document.getElementById('premium-newsletter-message');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Disable form
            button.disabled = true;
            button.textContent = 'Procesando...';
            
            try {
                const response = await fetch('{{ route("newsletter.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: formData.get('email'),
                        name: formData.get('name')
                    })
                });
                
                const data = await response.json();
                
                // Show message
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('hidden');
                
                if (data.success) {
                    messageDiv.classList.remove('text-red-500');
                    messageDiv.classList.add('text-green-600');
                    form.reset();
                } else {
                    messageDiv.classList.remove('text-green-600');
                    messageDiv.classList.add('text-red-500');
                }
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                }, 5000);
                
            } catch (error) {
                messageDiv.textContent = 'Error al procesar la solicitud';
                messageDiv.classList.remove('hidden', 'text-green-600');
                messageDiv.classList.add('text-red-500');
            } finally {
                // Re-enable form
                button.disabled = false;
                button.textContent = originalText;
            }
        });
    }
});
</script>