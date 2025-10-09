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
        Schema::create('income_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code',2);
            $table->unsignedBigInteger('income_id');
            $table->string('description');
            $table->boolean('is_deleted')->default(false);
            $table->foreign('income_id')->references('income_id')->on('income')->onDelete('cascade');
            $table->unique(['income_id','lang_code']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_translations');
    }
};
