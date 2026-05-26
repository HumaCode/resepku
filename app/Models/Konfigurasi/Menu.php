<?php

namespace App\Models\Konfigurasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

#[Fillable(['name', 'url', 'category', 'icon', 'is_active', 'orders'])]
class Menu extends Model
{
    use HasUlids;

    /**
     * Scope a query to only include active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    /**
     * Scope a query to search menus by name, url, or category.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        });
    }
}
