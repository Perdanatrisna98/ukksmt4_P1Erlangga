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
        Schema::create('tbl_classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('major_id')->constrained('tbl_majors')->cascadeOnDelete();
            $table->string('name');
            $table->integer('level');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_classrooms');
    }
};
