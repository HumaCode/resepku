<?php

namespace App\Repositories\Konten;

use App\Models\Konten\Recipe;

interface RecipeRepositoryInterface
{
    /**
     * Store a newly created recipe with all its relations.
     *
     * @param array $data
     * @return Recipe
     */
    public function create(array $data): Recipe;

    /**
     * Get paginated and filtered recipes.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters, int $perPage = 10);

    /**
     * Delete a recipe.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Update status of a recipe.
     *
     * @param string $id
     * @param string $status
     * @return Recipe
     */
    public function updateStatus(string $id, string $status): Recipe;

    /**
     * Find a recipe by its ID with all its relations.
     *
     * @param string $id
     * @return Recipe
     */
    public function findByIdWithRelations(string $id): Recipe;

    /**
     * Update featured status of a recipe.
     *
     * @param string $id
     * @param string $isFeatured
     * @return Recipe
     */
    public function updateFeatured(string $id, string $isFeatured): Recipe;

    /**
     * Update an existing recipe.
     *
     * @param string $id
     * @param array $data
     * @return Recipe
     */
    public function update(string $id, array $data): Recipe;
}
