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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('emoji', 10)->default('🥦');
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('default_unit');
            $table->text('description')->nullable();
            $table->char('is_active', 1)->default('1')->index();
            $table->integer('views')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
