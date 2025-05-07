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
        Schema::create('outcome', function (Blueprint $table) {
          $table->id('outcome_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('is_deleted')->default(0);

            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome');
    }
};
