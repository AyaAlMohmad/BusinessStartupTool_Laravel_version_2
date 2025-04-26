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
        Schema::create('simple_solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            // مفتاح خارجي للأعمال (اختياري)
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');
            $table->json('big_solution')->nullable(); // سؤال: What is my business big Solution?
            $table->json('entry_strategy')->nullable(); // سؤال: How can I enter the market in the most simple way?
            $table->json('things')->nullable(); // يحتوي على things_have و things_need
            $table->json('validation_questions')->nullable(); // سؤال: Questions you need to be answered
            $table->json('future_plan')->nullable(); // سؤال: What is your plan to move forward
            $table->json('notes')->nullable(); // سؤال: Notes
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simple_solutions');
    }
};
