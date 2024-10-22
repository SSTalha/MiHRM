<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('perks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('allowance');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('perks');
    }
};
