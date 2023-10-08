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
        Schema::create('convite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convidado_id')->constrained('conta')->onDelete('NO ACTION');
            $table->foreignId('dono_id')->constrained('conta')->onDelete('NO ACTION');
            $table->foreignId('racha_id')->constrained('racha')->onDelete('NO ACTION');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convite');

    }
};
