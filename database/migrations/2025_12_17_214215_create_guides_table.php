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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('tldr');
            $table->longText('content'); // markdown content
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->integer('estimated_minutes')->nullable();
            $table->json('os_tags')->nullable();
            $table->enum('status', ['draft', 'pending', 'published'])->default('draft');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
