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
        Schema::create('financial_plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('business_id'); 
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');

            $table->json('startup_costs')->nullable();
            $table->json('funding_sources')->nullable();
            $table->json('revenue_projections')->nullable();
            $table->json('expense_projections')->nullable();
            $table->json('breakeven_analysis')->nullable();
            $table->json('cash_flow_projections')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_plannings');
    }
};
