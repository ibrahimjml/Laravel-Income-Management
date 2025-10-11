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
        Schema::table('income', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->nullable()->after('subcategory_id'); 
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade'); 
            $table->decimal('final_amount', 10, 2)->after('amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_id');
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income', function (Blueprint $table) {
            $table->dropForeign(['discount_id']); 
            $table->dropColumn(['discount_id','final_amount','discount_amount']);  
        });
    }
};
