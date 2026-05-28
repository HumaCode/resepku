<?php

namespace App\Services\MasterData;

use App\Models\MasterData\Ingredient;
use App\Repositories\MasterData\IngredientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class IngredientService
{
    protected $ingredientRepository;

    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * Get all ingredients.
     */
    public function getAllIngredients(): Collection
    {
        return $this->ingredientRepository->getAll();
    }

    /**
     * Get paginated ingredients based on search & filter parameters.
     */
    public function getPaginatedIngredients(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->ingredientRepository->getPaginated($filters, $perPage);
    }

    /**
     * Store a newly created ingredient.
     */
    public function createIngredient(array $data): Ingredient
    {
        return $this->ingredientRepository->create($data);
    }

    /**
     * Find an ingredient by its ID.
     */
    public function findIngredient(string $id): ?Ingredient
    {
        return $this->ingredientRepository->find($id);
    }

    /**
     * Update the specified ingredient.
     */
    public function updateIngredient(Ingredient $ingredient, array $data): Ingredient
    {
        return $this->ingredientRepository->update($ingredient, $data);
    }

    /**
     * Delete the specified ingredient.
     */
    public function deleteIngredient(Ingredient $ingredient): bool
    {
        return $this->ingredientRepository->delete($ingredient);
    }

    /**
     * Toggle active status of an ingredient.
     */
    public function toggleActiveIngredient(Ingredient $ingredient): Ingredient
    {
        return $this->ingredientRepository->toggleActive($ingredient);
    }
}
