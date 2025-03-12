<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        $table->string('title')->nullable();
        $table->text('description')->nullable(); // visibile solo admin
        $table->dateTime('start');
        $table->dateTime('end')->nullable();
        $table->enum('status', ['libero', 'prenotato', 'completato', 'cancellato'])->default('libero');
        $table->string('color')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('appointments');
}

};
