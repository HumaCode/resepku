<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Category;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories with their parent and sub-categories count.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return Category::with('parent')
            ->withCount('children')
            ->orderBy('orders')
            ->get();
    }

    /**
     * Get paginated categories with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedCategories(array $filters, int $perPage = 6)
    {
        $query = Category::with('parent')
            ->withCount('children');

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $status = $filters['status'] === 'active' ? '1' : '0';
            $query->where('is_active', $status);
        }

        if (isset($filters['type']) && $filters['type'] !== 'all') {
            if ($filters['type'] === 'parent') {
                $query->whereNull('parent_id');
            } elseif ($filters['type'] === 'child') {
                $query->whereNotNull('parent_id');
            }
        }

        return $query->orderBy('orders')->paginate($perPage);
    }

    /**
     * Get parent categories only.
     *
     * @return Collection
     */
    public function getParentCategories(): Collection
    {
        return Category::whereNull('parent_id')
            ->orderBy('orders')
            ->get();
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        $category = Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? null,
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'is_active' => $data['is_active'] ?? '1',
            'orders' => $data['orders'] ?? 0,
            'views' => $data['views'] ?? 0,
        ]);

        if (isset($data['image']) && $data['image']->isValid()) {
            $category->addMedia($data['image'])->toMediaCollection('image');
        }

        return $category;
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? $category->icon,
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'is_active' => $data['is_active'] ?? $category->is_active,
            'orders' => $data['orders'] ?? $category->orders,
        ]);

        if (isset($data['image']) && $data['image']->isValid()) {
            $category->clearMediaCollection('image');
            $category->addMedia($data['image'])->toMediaCollection('image');
        } elseif (isset($data['remove_image']) && $data['remove_image'] === '1') {
            $category->clearMediaCollection('image');
        }

        return $category;
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool|null
     */
    public function deleteCategory(Category $category)
    {
        return $category->delete();
    }

    /**
     * Toggle the active status of a category.
     *
     * @param Category $category
     * @return Category
     */
    public function toggleCategoryStatus(Category $category): Category
    {
        $category->is_active = $category->is_active === '1' ? '0' : '1';
        $category->save();
        return $category;
    }
}
