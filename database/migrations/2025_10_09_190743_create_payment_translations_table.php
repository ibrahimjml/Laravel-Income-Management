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
        Schema::create('payment_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code',2);
            $table->unsignedBigInteger('payment_id');
            $table->string('description');
            $table->boolean('is_deleted')->default(false);
            $table->foreign('payment_id')->references('payment_id')->on('payments')->onDelete('cascade');
            $table->unique(['payment_id','lang_code']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_translations');
    }
};
