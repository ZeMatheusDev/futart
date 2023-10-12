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
        Schema::create('racha_confirmacao', function (Blueprint $table) {
            $table->id();
            $table->string('data_dia_racha');
            $table->boolean('confirmacao');
            $table->foreignId('racha_id')->constrained('racha')->onDelete('NO ACTION');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
