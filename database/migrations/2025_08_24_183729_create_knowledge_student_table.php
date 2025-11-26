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
        Schema::create('knowledge_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('knowledge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('knowledge_levels')->cascadeOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kiosk_id')->nullable()->constrained('kiosk_sessions')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'knowledge_id', 'level_id']); // each student can have only one level per knowledge
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_student');
    }
};
