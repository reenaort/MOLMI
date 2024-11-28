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
        Schema::create('course_mapping_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->comment('foreign key for courses')->nullable();
            $table->string('categories')->comment('foreign key for categories comma separated')->nullable();
            $table->string('subcategories')->comment('foreign key for sub_categories comma separated')->nullable();
            $table->string('vessels')->comment('foreign key for vessels comma separated')->nullable();
            $table->string('departments')->comment('foreign key for departments comma separated')->nullable();
            $table->string('ranks')->comment('foreign key for ranks comma separated')->nullable();
            $table->string('rank_priorities')->comment('1 - Mandatory, 2 - Recommended comma separated values for ranks column')->nullable();
            $table->tinyInteger('status')->comment('1 - Active, 0 - Inactive')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_mapping_history');
    }
};
