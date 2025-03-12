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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->string('name');
            $table->text('description');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('location_name');
            $table->string('location_address');
            $table->string('location_url')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->default('#000000');
            $table->boolean('is_public')->default(true);
            $table->string('age_group')->default('all');
            $table->timestamps();
        });
        Schema::create('users_eventos', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('evento_id')->constrained('eventos');
            $table->primary(['user_id', 'evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
