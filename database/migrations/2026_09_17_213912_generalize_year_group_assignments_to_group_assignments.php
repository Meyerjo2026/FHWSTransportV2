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
        Schema::table('year_group_assignments', function (Blueprint $table) {
            $table->dropUnique(['year']);
            $table->string('type')->default('year')->after('id');
            $table->renameColumn('year', 'value');
        });

        Schema::table('year_group_assignments', function (Blueprint $table) {
            $table->unique(['type', 'value']);
        });

        Schema::rename('year_group_assignments', 'group_assignments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('group_assignments', 'year_group_assignments');

        Schema::table('year_group_assignments', function (Blueprint $table) {
            $table->dropUnique(['type', 'value']);
            $table->renameColumn('value', 'year');
            $table->dropColumn('type');
        });

        Schema::table('year_group_assignments', function (Blueprint $table) {
            $table->unique('year');
        });
    }
};
