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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_week'); // 0 (Domenica) ➜ 6 (Sabato)
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('availabilities');
    }
    
};
