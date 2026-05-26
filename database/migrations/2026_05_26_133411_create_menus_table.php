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
        Schema::create('menus', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('url')->nullable()->index();
            $table->string('category')->index();
            $table->string('icon')->nullable();
            $table->enum('is_active', ['0', '1'])->default('1')->index();
            $table->integer('orders')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
