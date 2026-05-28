<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection;

    /**
     * Get paginated categories with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCategories(array $filters, int $perPage = 6);

    /**
     * Get parent categories only.
     *
     * @return Collection
     */
    public function getParentCategories(): Collection;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category;

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category;

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool|null
     */
    public function deleteCategory(Category $category);

    /**
     * Toggle the active status of a category.
     *
     * @param Category $category
     * @return Category
     */
    public function toggleCategoryStatus(Category $category): Category;
}
