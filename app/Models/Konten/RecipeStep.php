<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['recipe_id', 'step_number', 'description', 'image'])]
class RecipeStep extends Model
{
    use HasUlids;

    /**
     * Get the recipe that owns the cooking step.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
