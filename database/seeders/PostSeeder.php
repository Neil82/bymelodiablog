<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default user if none exists
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin ByMelodia',
                'email' => 'admin@bymelodia.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        $posts = [
            [
                'title' => '🎵 Los Ritmos Urbanos que Están Dominando 2025',
                'slug' => 'ritmos-urbanos-dominando-2025',
                'excerpt' => 'Descubre los géneros musicales que están conquistando las playlists de toda Latinoamérica este año.',
                'content' => '<h2>La Nueva Ola Musical Latinoamericana</h2>
                             <p>El reggaetón evolucionó, el trap se fusionó y nacieron sonidos completamente nuevos. En este 2025, los artistas jóvenes están experimentando con ritmos que mezclan lo tradicional con lo futurista.</p>
                             <h3>Los Géneros que Más Suenan</h3>
                             <ul>
                                <li><strong>Afro-Trap Latino:</strong> La fusión perfecta entre ritmos africanos y el trap urbano</li>
                                <li><strong>Reggaetón Experimental:</strong> Con toques electrónicos y letras más conscientes</li>
                                <li><strong>Cumbia Futurista:</strong> La cumbia tradicional reimaginada para la Generación Z</li>
                             </ul>
                             <p>Los DJs ya están incluyendo estos ritmos en sus sets, y las reproducciones en streaming no paran de crecer. ¿Ya tienes tus favoritos?</p>',
                'category_id' => Category::where('slug', 'musica')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'image_position' => 'top',
                'comments_enabled' => true,
            ],
            [
                'title' => '✨ El Aesthetic que Está Rompiendo Instagram',
                'slug' => 'aesthetic-rompiendo-instagram',
                'excerpt' => 'Desde el Dark Academia hasta el Y2K revival, exploramos las tendencias visuales que definen nuestra era digital.',
                'content' => '<h2>Más Allá de los Filtros: Aesthetics que Marcan Época</h2>
                             <p>Instagram dejó de ser solo una red social para convertirse en un moodboard gigante donde nacen y mueren tendencias visuales cada semana.</p>
                             <h3>Los Aesthetics Más Populares del Momento</h3>
                             <p><strong>🌙 Dark Academia:</strong> Bibliotecas antiguas, suéteres oversized y una vibra muy "estudiante de Oxford". Perfect para quienes aman la literatura y el café.</p>
                             <p><strong>🌸 Coquette:</strong> Rosa, encajes, bows y esa energía femenina ultra soft que está conquistando TikTok.</p>
                             <p><strong>🎮 Y2K Cyber:</strong> El futuro que imaginamos en los 2000s pero con un toque más realista y accesible.</p>
                             <blockquote>
                                <p>"Los aesthetics no son solo tendencias, son formas de expresar nuestra identidad en el mundo digital" - Experta en Cultura Digital</p>
                             </blockquote>
                             <p>Lo cool es que puedes mezclar elementos de diferentes aesthetics para crear tu propio estilo único. ¡La creatividad no tiene límites!</p>',
                'category_id' => Category::where('slug', 'tendencias')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'image_position' => 'right',
                'comments_enabled' => true,
            ],
            [
                'title' => '🎮 Gaming Culture: Más que Solo Jugar',
                'slug' => 'gaming-culture-mas-que-jugar',
                'excerpt' => 'El gaming se convirtió en un estilo de vida completo que abarca desde la moda hasta las relaciones sociales.',
                'content' => '<h2>Cuando Gaming se Vuelve Lifestyle</h2>
                             <p>Ya no se trata solo de completar niveles o ganar matches. El gaming creó su propia cultura, lenguaje y hasta códigos de vestimenta.</p>
                             <h3>🕹️ Más Allá de la Pantalla</h3>
                             <p>Los gamers de hoy:</p>
                             <ul>
                                <li>Crean contenido en Twitch y YouTube</li>
                                <li>Participan en comunidades globales</li>
                                <li>Usan gaming peripherals como fashion statements</li>
                                <li>Asisten a eventos presenciales y tournaments</li>
                             </ul>
                             <h3>🎨 La Estética Gaming</h3>
                             <p>RGB everywhere, setups minimalistas, mechanical keyboards que suenan como música... El aesthetic gamer evolucionó de "cuarto desordenado" a "estudio ultra profesional".</p>
                             <p>Incluso las marcas de moda se están inspirando en la cultura gaming para sus nuevas colecciones. ¿Quién hubiera pensado que un gaming chair sería parte del home decor?</p>',
                'category_id' => Category::where('slug', 'entretenimiento')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'image_position' => 'left',
                'comments_enabled' => true,
            ],
            [
                'title' => '💫 Self-Care para la Generación Z: Más Real, Menos Perfect',
                'slug' => 'self-care-generacion-z-real',
                'excerpt' => 'Olvidate de los face masks perfectos. El self-care actual es honesto, accesible y se adapta a nuestro ritmo de vida real.',
                'content' => '<h2>Self-Care Sin Filtros</h2>
                             <p>La Generación Z redefinió completamente el concepto de autocuidado. Nada de rutinas imposibles de seguir o productos súper caros.</p>
                             <h3>🌱 El Nuevo Self-Care es...</h3>
                             <p><strong>Micro-momentos:</strong> 5 minutos de meditación, no 1 hora.</p>
                             <p><strong>Budget-friendly:</strong> DIY masks con ingredientes de la cocina.</p>
                             <p><strong>Mental first:</strong> Terapia > productos de belleza.</p>
                             <p><strong>Community-based:</strong> Self-care en grupo, no solo individual.</p>
                             
                             <h3>💡 Ideas Realistas para tu Rutina</h3>
                             <ul>
                                <li><strong>Morning pages:</strong> 3 páginas de escritura libre cada mañana</li>
                                <li><strong>Playlist therapy:</strong> Una playlist diferente para cada mood</li>
                                <li><strong>Plant parent life:</strong> Cuidar plantas como forma de mindfulness</li>
                                <li><strong>Digital detox hours:</strong> 2 horas sin pantallas antes de dormir</li>
                             </ul>
                             
                             <p>Lo más importante: tu self-care debe sentirse bien para TI, no verse bien en Instagram.</p>',
                'category_id' => Category::where('slug', 'lifestyle')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'image_position' => 'bottom',
                'comments_enabled' => true,
            ],
            [
                'title' => '🎨 Arte Urbano Digital: Cuando el Street Art se Vuelve NFT',
                'slug' => 'arte-urbano-digital-nft',
                'excerpt' => 'Los murales ahora también viven en blockchain. Exploramos cómo los artistas urbanos están conquistando el mundo digital.',
                'content' => '<h2>Del Muro a la Wallet</h2>
                             <p>El street art siempre fue rebelde, pero ahora también es digital. Los artistas urbanos están tokenizando sus obras y creando experiencias completamente nuevas.</p>
                             
                             <h3>🖼️ La Revolución Artística Digital</h3>
                             <p>Imagínate poder "poseer" un pedazo de ese mural increíble que viste en el centro. Con los NFTs de arte urbano, eso ya es posible.</p>
                             
                             <h3>🌐 Artistas que Están Cambiando el Juego</h3>
                             <p>Desde Medellín hasta México DF, los muralistas están:</p>
                             <ul>
                                <li>Creando versiones digitales de sus murales físicos</li>
                                <li>Diseñando arte exclusivamente para espacios virtuales</li>
                                <li>Colaborando con marcas en experiencias AR</li>
                                <li>Vendiendo pieces directamente a collectors globales</li>
                             </ul>
                             
                             <h3>🚀 El Futuro del Arte Urbano</h3>
                             <p>Pronto podremos ver murales que cobran vida con realidad aumentada, o participar en la creación colaborativa de obras digitales. El arte urbano encontró una nueva pared: internet.</p>
                             
                             <blockquote>
                                <p>"El arte urbano siempre fue sobre democratizar el arte. Los NFTs son solo la siguiente evolución de esa filosofía." - Artista urbano anónimo</p>
                             </blockquote>',
                'category_id' => Category::where('slug', 'cultura')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'image_position' => 'top',
                'comments_enabled' => true,
            ],
            [
                'title' => '🌍 Sustainable Fashion: Thrifting como Lifestyle',
                'slug' => 'sustainable-fashion-thrifting-lifestyle',
                'excerpt' => 'La moda sostenible dejó de ser trend para volverse necesidad. Descubre cómo hacer thrifting como un pro.',
                'content' => '<h2>Thrifting: El Arte de Encontrar Tesoros</h2>
                             <p>Comprar ropa de segunda mano ya no es por necesidad económica, es una statement. Los Gen Z están redefiniendo el lujo a través de la sostenibilidad.</p>
                             
                             <h3>🛍️ Por Qué Todos Están Obsesionados con el Thrifting</h3>
                             <ul>
                                <li><strong>Unique pieces:</strong> Nadie más va a tener tu exact outfit</li>
                                <li><strong>Budget-friendly:</strong> Designer pieces a precios accesibles</li>
                                <li><strong>Eco-conscious:</strong> Reduces tu carbon footprint</li>
                                <li><strong>Treasure hunting:</strong> La emoción de encontrar THE piece</li>
                             </ul>
                             
                             <h3>💡 Pro Tips para el Thrifting Perfecto</h3>
                             <p><strong>🕒 Timing is everything:</strong> Ve temprano o en días específicos cuando llega mercancía nueva.</p>
                             <p><strong>👀 Look beyond the obvious:</strong> Esa chaqueta oversized puede ser perfecta con el styling correcto.</p>
                             <p><strong>🧵 Learn basic alterations:</strong> Un small tweak puede transformar completamente una prenda.</p>
                             <p><strong>📱 Use apps:</strong> Vinted, Depop y apps locales amplían tu hunting ground.</p>
                             
                             <h3>✨ Styling Your Thrifted Finds</h3>
                             <p>Mix vintage pieces con contemporary basics. Una blazer de los 80s + jeans actuales + sneakers = chef\'s kiss.</p>
                             
                             <p>El thrifting se volvió una forma de self-expression y conscious consumption al mismo tiempo. Plus, tu Instagram va a agradecer todos esos outfit posts únicos.</p>',
                'category_id' => Category::where('slug', 'tendencias')->first()->id,
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subHours(12),
                'image_position' => 'right',
                'comments_enabled' => true,
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}
