<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Ingredient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IngredientRepositoryInterface
{
    /**
     * Get all ingredients.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated ingredients based on search & filter parameters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Store a newly created ingredient.
     *
     * @param array $data
     * @return Ingredient
     */
    public function create(array $data): Ingredient;

    /**
     * Find an ingredient by its ID.
     *
     * @param string $id
     * @return Ingredient|null
     */
    public function find(string $id): ?Ingredient;

    /**
     * Update the specified ingredient.
     *
     * @param Ingredient $ingredient
     * @param array $data
     * @return Ingredient
     */
    public function update(Ingredient $ingredient, array $data): Ingredient;

    /**
     * Delete the specified ingredient.
     *
     * @param Ingredient $ingredient
     * @return bool
     */
    public function delete(Ingredient $ingredient): bool;

    /**
     * Toggle active status of an ingredient.
     *
     * @param Ingredient $ingredient
     * @return Ingredient
     */
    public function toggleActive(Ingredient $ingredient): Ingredient;
}
