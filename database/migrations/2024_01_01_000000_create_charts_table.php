<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('chart_type');
            $table->string('data_source');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charts');
    }
};
