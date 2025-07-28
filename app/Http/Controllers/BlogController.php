<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\SiteSetting;

class BlogController extends Controller
{
    public function index()
    {
        $currentLang = app()->getLocale();
        
        $posts = Post::published()
                    ->with(['category', 'user', 'translations.language'])
                    ->latest('published_at')
                    ->paginate(6);
        
        // Apply translations to posts
        $posts->getCollection()->transform(function ($post) use ($currentLang) {
            $translation = $post->getTranslation($currentLang);
            if ($translation) {
                $post->title = $translation->title;
                $post->excerpt = $translation->excerpt;
                $post->slug = $translation->slug;
            }
            return $post;
        });
                    
        $categories = Category::where('active', true)
                             ->withCount('publishedPosts')
                             ->get();
                             
        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        if (!$post->isPublished()) {
            abort(404);
        }

        $post->incrementViews();
        
        $currentLang = app()->getLocale();
        
        $post->load(['category', 'user', 'approvedComments' => function($query) {
            $query->latest();
        }, 'translations.language']);

        // Apply translation to main post
        $translation = $post->getTranslation($currentLang);
        if ($translation) {
            $post->title = $translation->title;
            $post->excerpt = $translation->excerpt;
            $post->content = $translation->content;
            $post->slug = $translation->slug;
        }

        $relatedPosts = Post::published()
                           ->where('category_id', $post->category_id)
                           ->where('id', '!=', $post->id)
                           ->with('translations.language')
                           ->limit(3)
                           ->get();

        // Apply translations to related posts
        $relatedPosts->transform(function ($relatedPost) use ($currentLang) {
            $translation = $relatedPost->getTranslation($currentLang);
            if ($translation) {
                $relatedPost->title = $translation->title;
                $relatedPost->excerpt = $translation->excerpt;
                $relatedPost->slug = $translation->slug;
            }
            return $relatedPost;
        });

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category(Category $category)
    {
        $currentLang = app()->getLocale();
        
        $posts = $category->publishedPosts()
                         ->with(['user', 'translations.language'])
                         ->latest('published_at')
                         ->paginate(6);

        // Apply translations to posts
        $posts->getCollection()->transform(function ($post) use ($currentLang) {
            $translation = $post->getTranslation($currentLang);
            if ($translation) {
                $post->title = $translation->title;
                $post->excerpt = $translation->excerpt;
                $post->slug = $translation->slug;
            }
            return $post;
        });

        return view('blog.category', compact('category', 'posts'));
    }

    public function storeComment(Request $request, Post $post)
    {
        if (!$post->comments_enabled) {
            return back()->with('error', 'Los comentarios están deshabilitados para este post.');
        }

        $validated = $request->validate([
            'author_name' => 'required|max:100',
            'author_email' => 'required|email|max:100',
            'content' => 'required|max:1000'
        ]);

        $comment = $post->comments()->create([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'pending'
        ]);

        return back()->with('success', 'Tu comentario ha sido enviado y está pendiente de moderación.');
    }

    public function privacyPolicy()
    {
        $content = SiteSetting::get('privacy_policy', '');
        
        if (empty($content)) {
            $content = $this->getDefaultPrivacyPolicy();
        }
        
        return view('legal.privacy-policy', compact('content'));
    }

    public function termsOfService()
    {
        $content = SiteSetting::get('terms_of_service', '');
        
        if (empty($content)) {
            $content = $this->getDefaultTermsOfService();
        }
        
        return view('legal.terms-of-service', compact('content'));
    }

    private function getDefaultPrivacyPolicy()
    {
        return "
# Política de Privacidad - ByMelodia

**Última actualización:** " . date('d/m/Y') . "

## 1. Información que Recopilamos

### Información que nos proporcionas directamente:
- Nombre y correo electrónico cuando comentas en nuestros posts
- Información de contacto cuando nos escribes

### Información que recopilamos automáticamente:
- Dirección IP y datos de navegación
- Cookies y tecnologías similares
- Estadísticas de uso del sitio

## 2. Cómo Utilizamos tu Información

Utilizamos la información recopilada para:
- Mostrar y moderar comentarios
- Mejorar nuestro contenido y experiencia del usuario
- Cumplir con obligaciones legales
- Mostrar anuncios personalizados (si has dado tu consentimiento)

## 3. Google AdSense

Este sitio utiliza Google AdSense para mostrar anuncios. Google puede usar cookies para mostrar anuncios basados en tus visitas anteriores a este u otros sitios web.

Puedes inhabilitar el uso de cookies de Google visitando la página de inhabilitación de publicidad de Google.

## 4. Cookies

Utilizamos cookies para:
- Recordar tus preferencias (como el modo oscuro/claro)
- Analizar el tráfico del sitio
- Mostrar anuncios relevantes (con tu consentimiento)

## 5. Tus Derechos

Tienes derecho a:
- Acceder a tu información personal
- Rectificar datos incorrectos
- Solicitar la eliminación de tus datos
- Retirar el consentimiento en cualquier momento

## 6. Jurisdicción y Ley Aplicable

ByMelodia es operado desde Perú. Estos términos se rigen por las leyes peruanas. Sin embargo, respetamos los derechos de privacidad de usuarios de todo el mundo, incluyendo las regulaciones internacionales como el GDPR para usuarios europeos.

## 7. Contacto

Para cualquier consulta sobre esta política de privacidad, puedes contactarnos a través de nuestro formulario de contacto.
        ";
    }

    private function getDefaultTermsOfService()
    {
        return "
# Términos de Servicio - ByMelodia

**Última actualización:** " . date('d/m/Y') . "

## 1. Aceptación de los Términos

Al acceder y usar ByMelodia, aceptas cumplir con estos términos de servicio.

## 2. Uso del Sitio

### Está permitido:
- Leer y compartir nuestro contenido
- Comentar de manera respetuosa
- Suscribirte a nuestras actualizaciones

### Está prohibido:
- Publicar contenido ofensivo, spam o inapropiado
- Intentar hackear o dañar el sitio
- Usar el contenido sin autorización para fines comerciales

## 3. Comentarios

- Los comentarios están sujetos a moderación
- Nos reservamos el derecho de eliminar comentarios inapropiados
- Al comentar, otorgas permiso para publicar tu comentario en el sitio

## 4. Propiedad Intelectual

Todo el contenido original de ByMelodia está protegido por derechos de autor. No puedes reproducir, distribuir o usar nuestro contenido sin permiso expreso.

## 5. Limitación de Responsabilidad

ByMelodia se proporciona 'tal como es'. No garantizamos que el sitio esté libre de errores o interrupciones.

## 6. Modificaciones

Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios entrarán en vigor inmediatamente después de su publicación.

## 7. Jurisdicción y Ley Aplicable

ByMelodia es operado desde Perú. Estos términos se rigen por las leyes peruanas. Sin embargo, al tener alcance global, respetamos las regulaciones internacionales aplicables según la ubicación de nuestros usuarios.

## 8. Contacto

Para preguntas sobre estos términos, contáctanos a través de nuestro formulario de contacto.
        ";
    }
}
