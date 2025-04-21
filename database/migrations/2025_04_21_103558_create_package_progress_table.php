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
        Schema::create('package_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            
            $table->string('status');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->boolean('returning')->default(false);
            $table->timestamp('datetime')->useCurrent();
            $table->timestamps();
            
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('campus_id')->references('id')->on('campuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_progress');
    }
};
