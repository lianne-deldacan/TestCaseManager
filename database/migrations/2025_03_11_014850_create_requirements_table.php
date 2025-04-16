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
        Schema::create('requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger('project_id')->index('requirements_project_id_foreign');
            $table->foreignId('project_id')->constrained('projects');
            $table->string('user');
            $table->string('title');
            $table->string('category');
            $table->string('type');
            $table->string('number');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
