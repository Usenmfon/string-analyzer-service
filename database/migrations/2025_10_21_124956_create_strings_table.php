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
        Schema::create('strings', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->text('value');
            $table->unsignedInteger('length')->index();
            $table->boolean('is_palindrome')->index();
            $table->unsignedInteger('unique_characters')->index();
            $table->unsignedInteger('word_count')->index();
            $table->json('properties');
            $table->unique(['value']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strings');
    }
};
