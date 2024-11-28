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
        Schema::create('course-enrollment-expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment('foreign key for courses')->nullable();
            $table->integer('can_id')->comment('foreign key for candidates')->nullable();
            $table->bigInteger('expenditure_amount')->nullable();
            $table->bigInteger('refund_amount')->nullable();
            $table->integer('status')->default(0)->comment('0-Pending,1 - Accepted, 2 - Declined')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course-enrollment-expenses');
    }
};
