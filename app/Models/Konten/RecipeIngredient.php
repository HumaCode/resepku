<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['recipe_id', 'name', 'amount', 'unit'])]
class RecipeIngredient extends Model
{
    use HasUlids;

    /**
     * Get the recipe that owns the ingredient.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the matching master ingredient by name.
     */
    public function masterIngredient(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\MasterData\Ingredient::class, 'name', 'name');
    }
}
