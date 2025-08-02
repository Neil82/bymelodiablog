<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\FeaturedPostController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\LanguageController as AdminLanguageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/categoria/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post:slug}/comments', [BlogController::class, 'storeComment'])->name('blog.comments.store');

// About page
Route::get('/acerca-de', function () {
    return view('about');
})->name('about');

// Legal pages
Route::get('/privacy-policy', [BlogController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-service', [BlogController::class, 'termsOfService'])->name('terms.service');

// Language routes
Route::get('/language/switch/{code}', [LanguageController::class, 'switch'])->name('language.switch');

// Newsletter routes
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [App\Http\Controllers\NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// API routes (public)
Route::prefix('api')->group(function () {
    Route::get('languages/available', [LanguageController::class, 'getAvailableLanguages']);
    Route::get('languages/detect-browser', [LanguageController::class, 'detectBrowserLanguage']);
});

// Analytics tracking API routes moved to routes/api.php

// SEO routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-posts.xml', [SitemapController::class, 'posts'])->name('sitemap.posts');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// Dashboard redirect to admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes (require authentication)
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('posts', AdminPostController::class)->names([
        'index' => 'admin.posts.index',
        'create' => 'admin.posts.create',
        'store' => 'admin.posts.store',
        'show' => 'admin.posts.show',
        'edit' => 'admin.posts.edit',
        'update' => 'admin.posts.update',
        'destroy' => 'admin.posts.destroy'
    ]);
    
    Route::resource('categories', AdminCategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy'
    ]);
    
    Route::get('comments', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('admin.comments.approve');
    Route::patch('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('admin.comments.reject');
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('admin.comments.destroy');
    
    // Image upload route
    Route::post('upload-image', [AdminPostController::class, 'uploadImage'])->name('admin.upload.image');
    
    // Settings routes
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('settings/upload-image', [AdminSettingsController::class, 'uploadImage'])->name('admin.settings.upload-image');
    
    // Featured Posts routes
    Route::get('featured-posts', [FeaturedPostController::class, 'index'])->name('admin.featured-posts.index');
    Route::post('featured-posts', [FeaturedPostController::class, 'store'])->name('admin.featured-posts.store');
    Route::delete('featured-posts/{featuredPost}', [FeaturedPostController::class, 'destroy'])->name('admin.featured-posts.destroy');
    Route::post('featured-posts/order', [FeaturedPostController::class, 'updateOrder'])->name('admin.featured-posts.order');
    
    // Analytics routes
    Route::prefix('analytics')->group(function () {
        Route::get('overview', [AnalyticsController::class, 'overview'])->name('admin.analytics.overview');
        Route::get('visitors', [AnalyticsController::class, 'visitors'])->name('admin.analytics.visitors');
        Route::get('posts', [AnalyticsController::class, 'posts'])->name('admin.analytics.posts');
        Route::get('realtime', [AnalyticsController::class, 'realtime'])->name('admin.analytics.realtime');
        Route::get('diagnostics', [DiagnosticController::class, 'index'])->name('admin.analytics.diagnostics');
        Route::get('chart-data', [AnalyticsController::class, 'getChartData'])->name('admin.analytics.chart-data');
    });
    
    // Language management routes
    Route::resource('languages', AdminLanguageController::class)->names([
        'index' => 'admin.languages.index',
        'create' => 'admin.languages.create', 
        'store' => 'admin.languages.store',
        'show' => 'admin.languages.show',
        'edit' => 'admin.languages.edit',
        'update' => 'admin.languages.update',
        'destroy' => 'admin.languages.destroy'
    ]);
    Route::patch('languages/{language}/toggle-status', [AdminLanguageController::class, 'toggleStatus'])->name('admin.languages.toggle-status');
    
    // User management routes
    Route::resource('users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);
    Route::get('change-password', [AdminUserController::class, 'changePassword'])->name('admin.users.change-password');
    Route::post('change-password', [AdminUserController::class, 'updatePassword'])->name('admin.users.update-password');
    
    // Newsletter management routes
    Route::get('newsletter', [AdminNewsletterController::class, 'index'])->name('admin.newsletter.index');
    Route::get('newsletter/export', [AdminNewsletterController::class, 'export'])->name('admin.newsletter.export');
    Route::delete('newsletter/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');
});

// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
