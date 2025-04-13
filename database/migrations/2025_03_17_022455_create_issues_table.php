<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            // Foreign Key Constraints
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            // $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->foreignId('test_case_id')->constrained('test_cases')->onDelete('cascade');
            // $table->foreign('execution_id')->references('id')->on('executions')->onDelete('cascade');
            $table->foreignId('developer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('tester_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('issue_number')->unique();
            // $table->unsignedBigInteger('project_id'); 
            // $table->unsignedBigInteger('execution_id');
            // $table->string('project_name')->nullable(); 
            $table->string('environment');
            // $table->string('status')->default('Open');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('issue_title');
            // $table->string('tester');
            $table->text('issue_description');
            $table->string('screenshot_url')->nullable();
            // $table->string('assigned_developer')->nullable(); 
            $table->timestamp('date_time_report'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
