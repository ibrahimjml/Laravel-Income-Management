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
        Schema::create('income', function (Blueprint $table) {
            $table->id('income_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'partial', 'complete']);
            $table->text('description')->nullable();
            $table->date('next_payment')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->boolean('is_deleted')->default(0);

            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategories')->onDelete('cascade');
            $table->foreign('client_id')->references('client_id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income');
    }
};
