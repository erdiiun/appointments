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
        Schema::create('company_work_days', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedInteger('day_of_week');
            $table->char('start_time');
            $table->char('end_time');
            $table->timestamps();

            $table->unique(['company_id', 'day_of_week']);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_work_days');
    }
};
