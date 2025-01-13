<?php

namespace Database\Migrations;

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
        Schema::create('grant_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('research_grants')->onDelete('cascade');
            $table->foreignId('academician_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['grant_id', 'academician_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_members');
    }
};
