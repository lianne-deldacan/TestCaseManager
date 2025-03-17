<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique();
            $table->unsignedBigInteger('project_id'); 
            $table->unsignedBigInteger('execution_id'); 
            $table->string('project_name')->nullable(); 
            $table->string('environment');
            $table->string('status')->default('Open');
            $table->string('issue_title');
            $table->string('tester');
            $table->text('issue_description');
            $table->string('screenshot_url')->nullable();
            $table->string('assigned_developer')->nullable(); 
            $table->timestamp('date_time_report'); 
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('execution_id')->references('id')->on('executions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
