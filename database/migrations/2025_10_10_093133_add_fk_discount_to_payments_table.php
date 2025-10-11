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
        Schema::table('payments', function (Blueprint $table) {
              $table->unsignedBigInteger('discount_id')->nullable(); 
              $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade'); 
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['discount_id']); 
            $table->dropColumn('discount_id');  
        });
    }
};
