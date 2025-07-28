<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_session_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // page_view, scroll, click, time_on_page, etc
            $table->string('url');
            $table->string('page_title')->nullable();
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('time_on_page')->nullable(); // seconds
            $table->integer('scroll_depth')->nullable(); // percentage
            $table->json('event_data')->nullable(); // additional event-specific data
            $table->string('element_clicked')->nullable(); // for click events
            $table->timestamp('event_time');
            $table->timestamps();
            
            $table->index(['user_session_id', 'event_time']);
            $table->index(['event_type', 'event_time']);
            $table->index(['post_id', 'event_time']);
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_events');
    }
};
