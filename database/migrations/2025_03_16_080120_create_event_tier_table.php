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
        Schema::create('event_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->enum('tier', ['PLATINUM', 'GOLD', 'SILVER', 'BRONZE', 'STANDARD'])->default('STANDARD');
            $table->integer('organizer_rating_score')->default(0);
            $table->integer('participation_score')->default(0);
            $table->integer('subscription_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tiers');
    }
};
