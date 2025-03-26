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
        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('test_case_id')->nullable()->after('execution_id');
            $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign(['test_case_id']);
            $table->dropColumn('test_case_id');
        });
    }
};
