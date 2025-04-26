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
        Schema::create('launch_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('launch_preparation_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('business_id'); 
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');

            $table->string('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->nullable();
            $table->json('dependencies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launch_milestones');
    }
};
