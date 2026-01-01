<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('registration_enabled')->default(false);
            $table->boolean('login_enabled')->default(true);
            $table->text('registration_disabled_message')->nullable();
            $table->text('login_disabled_message')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            'registration_enabled' => false,
            'login_enabled' => true,
            'registration_disabled_message' => 'Registration is temporarily disabled while we resolve an email deliverability issue. We expect it to be resolved within a few hours.',
            'login_disabled_message' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
