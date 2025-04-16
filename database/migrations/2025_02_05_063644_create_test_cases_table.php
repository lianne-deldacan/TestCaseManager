<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('test_cases', function (Blueprint $table) {
            $table->id();
            $table->string('test_case_no');
            $table->string('test_environment');
            $table->string('tester');
            $table->date('date_of_input');
            $table->string('test_title');
            $table->text('test_description');
            $table->enum('status', ['pass', 'fail']);
            $table->string('screenshot')->nullable(); // Screenshot as a string
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('severity', ['low', 'medium', 'high']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_cases');
    }
};
