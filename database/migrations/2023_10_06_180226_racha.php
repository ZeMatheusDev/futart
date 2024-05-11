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
        Schema::create('racha', function (Blueprint $table) {
            $table->id();
            $table->string('nome_do_racha');
            $table->string('descricao');
            $table->integer('quantidade');
            $table->integer('quantidade_maxima_jogo');
            $table->time('hora_do_racha');
            $table->time('final_do_racha');
            $table->boolean('mensalista_preferencia');
            $table->boolean('ativo');
            $table->string('data_do_racha');
            $table->string('racha_token')->unique();
            $table->foreignId('usuario_id')->constrained('conta')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racha');

    }
};
