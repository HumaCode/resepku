<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

#[Fillable(['emoji', 'name', 'slug', 'category', 'default_unit', 'description', 'is_active', 'views'])]
class Ingredient extends Model
{
    use HasUlids;

    /**
     * Scope a query to only include active ingredients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    /**
     * Scope a query to search ingredients by name or slug.
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('slug', 'like', "%{$keyword}%");
        });
    }
}
