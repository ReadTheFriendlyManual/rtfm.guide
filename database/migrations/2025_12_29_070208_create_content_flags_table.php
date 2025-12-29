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
        Schema::create('content_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('flaggable'); // flaggable_id and flaggable_type
            $table->string('reason');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, resolved, dismissed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Prevent duplicate flags from same user on same content
            $table->unique(['user_id', 'flaggable_id', 'flaggable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_flags');
    }
};
