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
        Schema::create('research_grants', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('grant_amount', 10, 2);
            $table->string('grant_provider');
            $table->date('start_date');
            $table->integer('duration');
            $table->foreignId('leader_id')->constrained('academicians')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_grants');
    }
};
