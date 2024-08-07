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
        Schema::create('conta_racha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('conta')->onDelete('NO ACTION');
            $table->foreignId('racha_id')->constrained('racha')->onDelete('NO ACTION');
            $table->boolean('mensalista');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conta_racha');
    }
};
