<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Ingredient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class IngredientRepository implements IngredientRepositoryInterface
{
    /**
     * Get all ingredients.
     */
    public function getAll(): Collection
    {
        return Ingredient::all();
    }

    /**
     * Get paginated ingredients based on search & filter parameters.
     */
    public function getPaginated(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Ingredient::query();

        // Filter by keyword search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Filter by category
        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $query->where('category', $filters['category']);
        }

        // Filter by active status
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $statusVal = $filters['status'] === 'active' ? '1' : '0';
            $query->where('is_active', $statusVal);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        
        // Ensure only valid columns can be sorted
        $validColumns = ['name', 'slug', 'category', 'views', 'created_at'];
        if (in_array($sortBy, $validColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Store a newly created ingredient.
     */
    public function create(array $data): Ingredient
    {
        return Ingredient::create($data);
    }

    /**
     * Find an ingredient by its ID.
     */
    public function find(string $id): ?Ingredient
    {
        return Ingredient::find($id);
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Ingredient $ingredient, array $data): Ingredient
    {
        $ingredient->update($data);
        return $ingredient;
    }

    /**
     * Delete the specified ingredient.
     */
    public function delete(Ingredient $ingredient): bool
    {
        return (bool) $ingredient->delete();
    }

    /**
     * Toggle active status of an ingredient.
     */
    public function toggleActive(Ingredient $ingredient): Ingredient
    {
        $newStatus = $ingredient->is_active === '1' ? '0' : '1';
        $ingredient->update(['is_active' => $newStatus]);
        return $ingredient;
    }
}
