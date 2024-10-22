<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('perks_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->json('requested_perks');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('total_allowance');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('perks_requests');
    }
};
