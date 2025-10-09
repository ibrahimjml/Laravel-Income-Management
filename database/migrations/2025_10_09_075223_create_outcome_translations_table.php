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
        Schema::create('outcome_translations', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code',2);
            $table->unsignedBigInteger('outcome_id');
            $table->string('description');
            $table->boolean('is_deleted')->default(false);
            $table->foreign('outcome_id')->references('outcome_id')->on('outcome')->onDelete('cascade');
            $table->unique(['outcome_id','lang_code']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outcome_translations');
    }
};
