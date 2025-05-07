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
        Schema::create('payments', function (Blueprint $table) {
          $table->id('payment_id');
          $table->unsignedBigInteger('income_id');
          $table->decimal('payment_amount', 10, 2)->default(0);
          $table->text('description')->nullable();
          $table->timestamp('created_at')->useCurrent();
          $table->boolean('is_deleted')->default(0);
          $table->foreign('income_id')->references('income_id')->on('income')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
