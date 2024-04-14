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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('available_id')->constrained('availabilities')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('start_at')->nullable(); 
            $table->string('topic')->nullable(); 
            $table->string('meeting_id')->nullable(); 
            $table->string('password')->nullable(); 
            $table->text('start_url')->nullable(); 
            $table->text('join_url')->nullable();
            $table->integer('duration')->default(60); 
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
