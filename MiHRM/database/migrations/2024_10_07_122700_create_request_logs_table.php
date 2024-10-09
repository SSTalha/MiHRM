<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');  // Foreign key with cascade delete
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('request_method');
            $table->text('request_url');
            $table->json('request_body')->nullable();
            $table->json('response_body')->nullable();
            $table->integer('response_status_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_logs');
    }
};
