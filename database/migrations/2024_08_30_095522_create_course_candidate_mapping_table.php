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
        Schema::create('course_candidate_mapping', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment('foreign key for courses')->nullable();
            $table->integer('can_id')->comment('foreign key for candidates')->nullable();
            $table->date('certificate_date')->nullable();
            $table->integer('status')->default(1)->comment('1 - Done, 2 - Undone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_candidate_mapping');
    }
};
