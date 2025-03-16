<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_translates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index(); // Misol: en, uz, ru
            $table->string('name');
            $table->unique(['status_id', 'locale']);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_translates');
    }
};
