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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_photo')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->integer('dep_id')->comment('foreign key for departments')->nullable();
            $table->integer('rank_id')->comment('foreign key for ranks')->nullable();
            $table->string('coc_no')->nullable();
            $table->string('endorse_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->integer('type')->comment('1 - Offshore, 2 - Onshore')->nullable();
            $table->date('till_date')->comment('if type is 2 then only')->nullable();
            $table->string('passport_file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
