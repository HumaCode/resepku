<?php

namespace App\Repositories\Konten;

use App\Models\Konten\Recipe;
use Illuminate\Support\Facades\DB;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * Store a newly created recipe with all its relations.
     */
    public function create(array $data): Recipe
    {
        return DB::transaction(function () use ($data) {
            // 1. Create main recipe record
            $recipe = Recipe::create([
                'user_id' => auth()->id(),
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'content' => $data['content'],
                'difficulty' => $data['difficulty'],
                'prep_time' => $data['prep_time'],
                'cook_time' => $data['cook_time'],
                'servings' => $data['servings'],
                'calories' => $data['calories'] ?? null,
                'protein' => $data['protein'] ?? null,
                'fat' => $data['fat'] ?? null,
                'carbs' => $data['carbs'] ?? null,
                'fiber' => $data['fiber'] ?? null,
                'sugar' => $data['sugar'] ?? null,
                'is_featured' => $data['is_featured'] ?? '0',
                'enable_comments' => $data['enable_comments'] ?? '1',
                'enable_ratings' => $data['enable_ratings'] ?? '1',
                'status' => $data['status'] ?? 'pending',
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            // 2. Upload cover image if provided
            if (isset($data['cover']) && $data['cover']->isValid()) {
                $recipe->addMedia($data['cover'])->toMediaCollection('cover');
            }

            // 3. Store ingredients
            if (!empty($data['ingredients'])) {
                $recipe->ingredients()->createMany($data['ingredients']);
            }

            // 4. Store cooking steps
            if (!empty($data['steps'])) {
                foreach ($data['steps'] as $stepData) {
                    $stepRecord = $recipe->steps()->create([
                        'step_number' => $stepData['step_number'],
                        'description' => $stepData['description'],
                    ]);
                    // Upload step image if provided
                    if (!empty($stepData['image_file']) && $stepData['image_file']->isValid()) {
                        $path = $stepData['image_file']->store('recipe-steps', 'public');
                        $stepRecord->update(['image' => $path]);
                    }
                }
            }

            // 5. Store videos
            if (!empty($data['videos'])) {
                $videoData = [];
                foreach ($data['videos'] as $index => $video) {
                    if (!empty($video['video_provider']) && !empty($video['video_url'])) {
                        $videoData[] = [
                            'video_provider' => $video['video_provider'],
                            'video_url' => $video['video_url'],
                            'orders' => $index + 1,
                        ];
                    }
                }
                if (!empty($videoData)) {
                    $recipe->videos()->createMany($videoData);
                }
            }

            // 6. Sync tags
            if (!empty($data['tags'])) {
                $recipe->tags()->sync($data['tags']);
            }

            return $recipe;
        });
    }

    /**
     * Get paginated and filtered recipes.
     */
    public function getPaginated(array $filters, int $perPage = 10)
    {
        $query = Recipe::query()
            ->with(['category', 'user', 'tags'])
            ->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $query->whereHas('category', function ($cq) use ($filters) {
                $cq->where('slug', $filters['category']);
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Delete a recipe.
     */
    public function delete(string $id): bool
    {
        $recipe = Recipe::find($id);
        if ($recipe) {
            return (bool) $recipe->delete();
        }
        return false;
    }

    /**
     * Update status of a recipe.
     */
    public function updateStatus(string $id, string $status): Recipe
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update(['status' => $status]);
        return $recipe;
    }

    /**
     * Find a recipe by its ID with all its relations.
     */
    public function findByIdWithRelations(string $id): Recipe
    {
        return Recipe::with(['category', 'user', 'tags', 'ingredients', 'steps', 'videos'])->findOrFail($id);
    }

    /**
     * Update featured status of a recipe.
     */
    public function updateFeatured(string $id, string $isFeatured): Recipe
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update(['is_featured' => $isFeatured]);
        return $recipe;
    }

    /**
     * Update an existing recipe.
     */
    public function update(string $id, array $data): Recipe
    {
        return DB::transaction(function () use ($id, $data) {
            $recipe = Recipe::findOrFail($id);
            
            // 1. Update main recipe record
            $recipe->update([
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'content' => $data['content'],
                'difficulty' => $data['difficulty'],
                'prep_time' => $data['prep_time'],
                'cook_time' => $data['cook_time'],
                'servings' => $data['servings'],
                'calories' => $data['calories'] ?? null,
                'protein' => $data['protein'] ?? null,
                'fat' => $data['fat'] ?? null,
                'carbs' => $data['carbs'] ?? null,
                'fiber' => $data['fiber'] ?? null,
                'sugar' => $data['sugar'] ?? null,
                'is_featured' => $data['is_featured'] ?? '0',
                'enable_comments' => $data['enable_comments'] ?? '1',
                'enable_ratings' => $data['enable_ratings'] ?? '1',
                'status' => $data['status'] ?? 'pending',
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            // 2. Upload cover image if provided
            if (isset($data['cover']) && $data['cover']->isValid()) {
                $recipe->clearMediaCollection('cover');
                $recipe->addMedia($data['cover'])->toMediaCollection('cover');
            }

            // 3. Store ingredients
            $recipe->ingredients()->delete();
            if (!empty($data['ingredients'])) {
                $recipe->ingredients()->createMany($data['ingredients']);
            }

            // 4. Store cooking steps
            $recipe->steps()->delete();
            if (!empty($data['steps'])) {
                foreach ($data['steps'] as $stepData) {
                    $stepRecord = $recipe->steps()->create([
                        'step_number' => $stepData['step_number'],
                        'description' => $stepData['description'],
                    ]);
                    // Upload step image if provided
                    if (!empty($stepData['image_file']) && $stepData['image_file']->isValid()) {
                        $path = $stepData['image_file']->store('recipe-steps', 'public');
                        $stepRecord->update(['image' => $path]);
                    }
                }
            }

            // 5. Store videos
            $recipe->videos()->delete();
            if (!empty($data['videos'])) {
                $videoData = [];
                foreach ($data['videos'] as $index => $video) {
                    if (!empty($video['video_provider']) && !empty($video['video_url'])) {
                        $videoData[] = [
                            'video_provider' => $video['video_provider'],
                            'video_url' => $video['video_url'],
                            'orders' => $index + 1,
                        ];
                    }
                }
                if (!empty($videoData)) {
                    $recipe->videos()->createMany($videoData);
                }
            }

            // 6. Sync tags
            if (isset($data['tags'])) {
                $recipe->tags()->sync($data['tags']);
            } else {
                $recipe->tags()->detach();
            }

            return $recipe;
        });
    }
}
