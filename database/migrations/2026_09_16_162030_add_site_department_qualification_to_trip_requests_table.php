<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trip_requests', function (Blueprint $table) {
            $table->foreignId('clinical_site_id')->nullable()->after('student_number')->constrained('clinical_sites')->nullOnDelete();
            $table->string('department')->nullable()->after('notes');
            $table->string('qualification')->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('trip_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('clinical_site_id');
            $table->dropColumn(['department', 'qualification']);
        });
    }
};
