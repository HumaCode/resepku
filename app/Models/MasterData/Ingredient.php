<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory, HasUlids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'emoji',
        'name',
        'slug',
        'category',
        'default_unit',
        'description',
        'is_active',
        'views',
    ];

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

    /**
     * Scope a query to only include active ingredients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }
}
