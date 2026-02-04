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
        Schema::create('animales', function (Blueprint $table) {
            $table->id('id_animal');
            $table->string('nombre')->nullable(false);
            $table->enum('tipo', ['perro', 'gato', 'hámster', 'conejo'])->nullable(false);
            $table->decimal('peso');
            $table->string('enfermedad');
            $table->longText('comentarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animales');
    }
};
