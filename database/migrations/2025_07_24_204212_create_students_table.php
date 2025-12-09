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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->dateTime('birth_date');

            $table->string('nickname')->unique();
            $table->string('access_pin', 4);
            $table->string('avatar')->default('YOUR-TEXT.png');
            $table->string('background_image')->nullable();
            $table->string('theme')->default('dark');

            $table->integer('exp')->default(0);
            $table->integer('karma')->default(0);
            $table->integer('bucks')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
