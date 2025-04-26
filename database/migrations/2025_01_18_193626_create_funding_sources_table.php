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
        Schema::create('funding_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('financial_planning_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('business_id'); 
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');

            $table->string('source')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->string('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funding_sources');
    }
};
