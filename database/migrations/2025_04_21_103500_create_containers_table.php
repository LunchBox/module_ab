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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_campus_id');
            $table->unsignedBigInteger('to_campus_id');
            $table->unsignedBigInteger('trucker_id')->nullable();
            $table->enum('status', ['waiting', 'loaded', 'unloaded'])->default('waiting');
            $table->timestamps();
            
            $table->foreign('from_campus_id')->references('id')->on('campuses');
            $table->foreign('to_campus_id')->references('id')->on('campuses');
            $table->foreign('trucker_id')->references('id')->on('staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
