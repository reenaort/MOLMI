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
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->integer('cat_id')->comment('foreign key for categories')->nullable();
            $table->integer('subcat_id')->comment('foreign key for sub categories')->nullable();
            $table->string('vessel_name')->nullable();
            $table->integer('status')->comment('1 - Active, 2 - Invactive')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
