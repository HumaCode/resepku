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
        // 1. Recipes Table
        Schema::create('recipes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->nullable()->index();
            $table->ulid('category_id')->nullable()->index();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('content');
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit', 'expert'])->default('sedang');
            $table->integer('prep_time'); // in minutes
            $table->integer('cook_time'); // in minutes
            $table->integer('servings');
            
            // Nutrition (optional)
            $table->integer('calories')->nullable();
            $table->integer('protein')->nullable(); // in grams
            $table->integer('fat')->nullable(); // in grams
            $table->integer('carbs')->nullable(); // in grams
            $table->integer('fiber')->nullable(); // in grams
            $table->integer('sugar')->nullable(); // in grams

            // Toggles & Settings
            $table->enum('is_featured', ['0', '1'])->default('0')->index();
            $table->enum('enable_comments', ['0', '1'])->default('1');
            $table->enum('enable_ratings', ['0', '1'])->default('1');
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();

            // SEO Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Stats
            $table->integer('views')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);

            $table->timestamps();

            // Indexes for sorting & filtering
            $table->index('difficulty');
            $table->index(['status', 'is_featured']);
            $table->index(['status', 'category_id']);
            $table->index(['status', 'created_at']);
            $table->index(['status', 'views']);
            $table->index(['status', 'rating']);

            // Foreign Keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });

        // 2. Recipe Videos Detail Table (Supports Multiple Videos)
        Schema::create('recipe_videos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('recipe_id')->index();
            $table->string('video_url');
            $table->string('video_provider'); // youtube, instagram, tiktok, etc.
            $table->integer('orders')->default(0);
            $table->timestamps();

            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->cascadeOnDelete();
        });

        // 3. Recipe Ingredients Detail Table
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('recipe_id')->index();
            $table->string('name');
            $table->string('amount'); // quantity (e.g. 500, 1.5, 1/2)
            $table->string('unit'); // unit (e.g. gram, siung, sdm)
            $table->timestamps();

            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->cascadeOnDelete();
        });

        // 4. Recipe Cooking Steps Table
        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('recipe_id')->index();
            $table->integer('step_number')->index();
            $table->text('description');
            $table->timestamps();

            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->cascadeOnDelete();
        });

        // 5. Recipe Pivot Tag Table
        Schema::create('recipe_tag', function (Blueprint $table) {
            $table->ulid('recipe_id');
            $table->ulid('tag_id');

            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->cascadeOnDelete();

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->cascadeOnDelete();

            $table->primary(['recipe_id', 'tag_id']);
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tag');
        Schema::dropIfExists('recipe_steps');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipe_videos');
        Schema::dropIfExists('recipes');
    }
};
