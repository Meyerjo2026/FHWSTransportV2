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
        Schema::create('trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_name');
            $table->string('student_email');
            $table->string('student_number')->nullable();
            $table->string('site');
            $table->string('date'); // YYYY-MM-DD
            $table->string('time');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected | finalised
            $table->string('source')->default('manual'); // manual | bulk
            $table->string('uploaded_by')->nullable();
            $table->foreignId('quote_id')->nullable()->constrained('quotes')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_requests');
    }
};
