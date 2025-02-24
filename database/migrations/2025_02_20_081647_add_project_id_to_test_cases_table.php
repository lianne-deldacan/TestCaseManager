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
        Schema::table('test_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('test_cases', 'project_id')) {
                $table->unsignedBigInteger('project_id')->after('id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });

    }
};
