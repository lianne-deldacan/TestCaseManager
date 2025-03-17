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
        Schema::create('executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_case_id')->nullable()->constrained('test_cases')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('environment', ['UAT', 'SIT'])->default('SIT'); 
            $table->string('tester_name');
            $table->enum('status', ['Pending', 'Not Started', 'Running', 'Passed', 'Failed'])->default('Pending');
            $table->timestamps();
        });

        // Add an index for better performance
        Schema::table('executions', function (Blueprint $table) {
            $table->index(['project_id', 'test_case_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executions');
    }
};
