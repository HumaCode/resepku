<?php

namespace App\Services\Konten;

use App\Models\Konten\Recipe;
use App\Repositories\Konten\RecipeRepositoryInterface;

class RecipeService
{
    protected $recipeRepository;

    public function __construct(RecipeRepositoryInterface $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Store a newly created recipe.
     */
    public function createRecipe(array $data): Recipe
    {
        return $this->recipeRepository->create($data);
    }

    /**
     * Get paginated recipes.
     */
    public function getRecipes(array $filters, int $perPage = 10)
    {
        return $this->recipeRepository->getPaginated($filters, $perPage);
    }

    /**
     * Delete a recipe.
     */
    public function deleteRecipe(string $id): bool
    {
        return $this->recipeRepository->delete($id);
    }

    /**
     * Update recipe status.
     */
    public function updateRecipeStatus(string $id, string $status): Recipe
    {
        return $this->recipeRepository->updateStatus($id, $status);
    }

    /**
     * Get a recipe by its ID with all relations.
     */
    public function getRecipeById(string $id): Recipe
    {
        return $this->recipeRepository->findByIdWithRelations($id);
    }

    /**
     * Toggle recipe status.
     */
    public function toggleRecipeStatus(string $id): Recipe
    {
        $recipe = $this->recipeRepository->findByIdWithRelations($id);
        $newStatus = $recipe->status === 'published' ? 'draft' : 'published';
        return $this->recipeRepository->updateStatus($id, $newStatus);
    }

    /**
     * Toggle recipe featured status.
     */
    public function toggleRecipeFeatured(string $id): Recipe
    {
        $recipe = $this->recipeRepository->findByIdWithRelations($id);
        $newFeatured = $recipe->is_featured === '1' ? '0' : '1';
        return $this->recipeRepository->updateFeatured($id, $newFeatured);
    }

    /**
     * Duplicate a recipe as draft.
     */
    public function duplicateRecipe(string $id): Recipe
    {
        $recipe = $this->recipeRepository->findByIdWithRelations($id);
        
        return \Illuminate\Support\Facades\DB::transaction(function () use ($recipe) {
            $duplicate = $recipe->replicate();
            $duplicate->title = $recipe->title . ' (Duplikat)';
            $duplicate->slug = $recipe->slug . '-duplikat-' . time();
            $duplicate->status = 'draft';
            $duplicate->is_featured = '0';
            $duplicate->save();
            
            // Duplicate ingredients
            foreach ($recipe->ingredients as $ingredient) {
                $duplicate->ingredients()->create([
                    'name' => $ingredient->name,
                    'amount' => $ingredient->amount,
                    'unit' => $ingredient->unit,
                    'notes' => $ingredient->notes,
                ]);
            }
            
            // Duplicate steps
            foreach ($recipe->steps as $step) {
                $duplicate->steps()->create([
                    'step_number' => $step->step_number,
                    'description' => $step->description,
                ]);
            }
            
            // Duplicate videos
            foreach ($recipe->videos as $video) {
                $duplicate->videos()->create([
                    'video_provider' => $video->video_provider,
                    'video_url' => $video->video_url,
                    'orders' => $video->orders,
                ]);
            }
            
            // Duplicate tags
            $duplicate->tags()->sync($recipe->tags->pluck('id')->toArray());
            
            return $duplicate;
        });
    }

    /**
     * Update an existing recipe.
     */
    public function updateRecipe(string $id, array $data): Recipe
    {
        return $this->recipeRepository->update($id, $data);
    }
}
