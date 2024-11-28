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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name')->nullable();
            $table->string('course_code')->nullable();
            $table->tinyInteger('course_by')->comment('1 - Molmi, 2 - External')->default(1)->nullable();
            $table->string('course_logo')->nullable();
            $table->string('training_center')->comment('foreign key for training_centers comma separated')->nullable();
            $table->float('duration')->comment('in days')->nullable();
            $table->string('course_type')->nullable();
            $table->integer('course_followed_by')->comment('foreign key for courses')->nullable();
            $table->integer('course_repeated')->comment('1 - Repeated, 0 - Constant')->default(0)->nullable();
            $table->integer('course_intervals')->comment('if repeated then after intervals (in years)')->default(0)->nullable();
            $table->tinyInteger('online_priority')->comment('1 - High, 2 - Med, 3 - Low')->nullable();
            $table->tinyInteger('offline_priority')->comment('1 - High, 2 - Med, 3 - Low')->nullable();
            $table->tinyInteger('elearning_priority')->comment('1 - High, 2 - Med, 3 - Low')->nullable();
            $table->text('course_comments')->nullable();
            $table->string('categories')->comment('foreign key for categories comma separated')->nullable();
            $table->string('subcategories')->comment('foreign key for sub_categories comma separated')->nullable();
            $table->string('vessels')->comment('foreign key for vessels comma separated')->nullable();
            $table->string('departments')->comment('foreign key for departments comma separated')->nullable();
            $table->string('ranks')->comment('foreign key for ranks comma separated')->nullable();
            $table->string('rank_priorities')->comment('1 - Mandatory, 2 - Recommended comma separated values for ranks column')->nullable();
            $table->tinyInteger('status')->comment('1 - Active, 2 - Invactive')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
