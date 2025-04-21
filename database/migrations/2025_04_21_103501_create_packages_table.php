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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            
            $table->string('tracking_number')->unique();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('from_campus_id');
            $table->string('from_address');
            $table->unsignedBigInteger('to_campus_id');
            $table->string('to_address');
            $table->string('recipient_name');
            $table->string('recipient_phone_number', 12);
            $table->enum('status', [
                'Pending pickup', 
                'Picked up', 
                'Pending transit', 
                'In transit', 
                'Pending delivery', 
                'Delivering', 
                'Signed'
            ])->default('Pending pickup');
            $table->boolean('returning')->default(false);
            $table->unsignedBigInteger('pickup_courier_id')->nullable();
            $table->unsignedBigInteger('delivery_courier_id')->nullable();
            $table->unsignedBigInteger('container_id')->nullable();
            $table->timestamps();
            
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('from_campus_id')->references('id')->on('campuses');
            $table->foreign('to_campus_id')->references('id')->on('campuses');
            $table->foreign('pickup_courier_id')->references('id')->on('staff');
            $table->foreign('delivery_courier_id')->references('id')->on('staff');
            $table->foreign('container_id')->references('id')->on('containers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
