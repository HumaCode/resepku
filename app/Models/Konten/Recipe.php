<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\MasterData\Tag;

#[Fillable([
    'user_id',
    'category_id',
    'title',
    'slug',
    'description',
    'content',
    'difficulty',
    'prep_time',
    'cook_time',
    'servings',
    'calories',
    'protein',
    'fat',
    'carbs',
    'fiber',
    'sugar',
    'is_featured',
    'enable_comments',
    'enable_ratings',
    'status',
    'meta_title',
    'meta_description',
    'views',
    'rating',
])]
class Recipe extends Model implements HasMedia
{
    use HasUlids, InteractsWithMedia;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prep_time' => 'integer',
            'cook_time' => 'integer',
            'servings' => 'integer',
            'calories' => 'integer',
            'protein' => 'integer',
            'fat' => 'integer',
            'carbs' => 'integer',
            'fiber' => 'integer',
            'sugar' => 'integer',
            'views' => 'integer',
            'rating' => 'float',
        ];
    }

    /**
     * Get the user who wrote the recipe.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the recipe.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the ingredients of the recipe.
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    /**
     * Get the cooking steps of the recipe.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    /**
     * Get the video tutorials of the recipe.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(RecipeVideo::class)->orderBy('orders');
    }

    /**
     * Get the tags of the recipe.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();
    }

    /**
     * Scope a query to only include published recipes.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured recipes.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', '1');
    }

    /**
     * Scope a query to search recipes by title, description, or content.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }
}
