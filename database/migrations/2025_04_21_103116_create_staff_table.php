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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->string('password');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone_number', 12)->nullable();
            $table->enum('role', ['courier', 'trucker']);
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->string('plate_number', 12)->nullable();
            $table->boolean('online')->default(false);
            $table->integer('remaining_capacity')->default(5); // 快递员默认5，卡车司机默认15
            $table->integer('total_picked')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('total_unloaded')->default(0);
            $table->timestamps();
            
            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('route_id')->references('id')->on('routes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
