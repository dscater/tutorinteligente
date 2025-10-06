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
        Schema::create('cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->string("seccion", 200);
            $table->string("pregunta", 1000);
            $table->string("resp1", 800);
            $table->string("resp2", 800);
            $table->string("resp3", 800);
            $table->string("resp4", 800);
            $table->integer("correcta");
            $table->date("fecha_registro");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuestionarios');
    }
};
