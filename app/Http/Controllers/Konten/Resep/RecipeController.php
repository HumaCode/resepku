<?php

namespace App\Http\Controllers\Konten\Resep;

use App\Http\Controllers\Controller;
use App\Http\Requests\Konten\RecipeStoreRequest;
use App\Http\Requests\Konten\RecipeUpdateRequest;
use App\Http\Resources\Konten\RecipeResource;
use App\Models\MasterData\Category;
use App\Models\MasterData\Tag;
use App\Models\MasterData\Ingredient;
use App\Services\Konten\RecipeService;
use Illuminate\Http\Request;

use App\Http\Resources\PaginateResource;

class RecipeController extends Controller
{
    protected $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $filters = $request->only(['search', 'status', 'category']);
            $perPage = $request->integer('per_page', 10);
            
            $recipes = $this->recipeService->getRecipes($filters, $perPage);
            
            return new PaginateResource($recipes, RecipeResource::class);
        }

        $categories = Category::where('is_active', '1')->orderBy('orders')->get();
        $stats = [
            'total' => \App\Models\Konten\Recipe::count(),
            'published' => \App\Models\Konten\Recipe::where('status', 'published')->count(),
            'pending' => \App\Models\Konten\Recipe::where('status', 'pending')->count(),
            'draft' => \App\Models\Konten\Recipe::where('status', 'draft')->count(),
            'rejected' => \App\Models\Konten\Recipe::where('status', 'rejected')->count(),
        ];

        return view('pages.konten.resep.index', compact('categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', '1')->orderBy('orders')->get();
        $tags = Tag::where('is_active', '1')->orderBy('name')->get();
        $masterIngredients = Ingredient::active()->orderBy('name')->get();

        return view('pages.konten.resep.create', compact('categories', 'tags', 'masterIngredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecipeStoreRequest $request)
    {
        $data = $request->validated();

        // Attach uploaded step image files to their respective step data
        if (!empty($data['steps'])) {
            foreach ($data['steps'] as $index => $step) {
                $fileKey = "steps.{$index}.image_file";
                if ($request->hasFile($fileKey)) {
                    $data['steps'][$index]['image_file'] = $request->file($fileKey);
                }
            }
        }

        $recipe = $this->recipeService->createRecipe($data);

        return response()->json([
            'success' => true,
            'message' => $recipe->status === 'published' 
                ? 'Resep berhasil dipublikasikan!' 
                : 'Draft resep berhasil disimpan!',
            'data' => new RecipeResource($recipe),
            'redirect' => route('recipes.index')
        ]);
    }

    /**
     * Approve a pending recipe.
     */
    public function approve(string $id)
    {
        $recipe = $this->recipeService->updateRecipeStatus($id, 'published');

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil disetujui dan dipublikasikan!',
            'data' => new RecipeResource($recipe)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->recipeService->deleteRecipe($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Resep berhasil dihapus!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Resep tidak ditemukan atau gagal dihapus.'
        ], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = $this->recipeService->getRecipeById($id);

        return view('pages.konten.resep.show', compact('recipe'));
    }

    /**
     * Toggle the status of a recipe.
     */
    public function toggleStatus(string $id)
    {
        $recipe = $this->recipeService->toggleRecipeStatus($id);

        return response()->json([
            'success' => true,
            'message' => 'Status resep berhasil diubah!',
            'status' => $recipe->status
        ]);
    }

    /**
     * Toggle the featured status of a recipe.
     */
    public function toggleFeatured(string $id)
    {
        $recipe = $this->recipeService->toggleRecipeFeatured($id);

        return response()->json([
            'success' => true,
            'message' => $recipe->is_featured === '1' 
                ? 'Resep berhasil ditandai sebagai unggulan!' 
                : 'Resep dilepas dari unggulan!',
            'is_featured' => $recipe->is_featured
        ]);
    }

    /**
     * Duplicate a recipe.
     */
    public function duplicate(string $id)
    {
        $duplicate = $this->recipeService->duplicateRecipe($id);

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil diduplikat sebagai draft!',
            'redirect' => route('recipes.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $recipe = $this->recipeService->getRecipeById($id);
        $categories = Category::where('is_active', '1')->orderBy('orders')->get();
        $tags = Tag::where('is_active', '1')->orderBy('name')->get();
        $masterIngredients = Ingredient::active()->orderBy('name')->get();

        return view('pages.konten.resep.edit', compact('recipe', 'categories', 'tags', 'masterIngredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecipeUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        // Attach uploaded step image files to their respective step data
        if (!empty($data['steps'])) {
            foreach ($data['steps'] as $index => $step) {
                $fileKey = "steps.{$index}.image_file";
                if ($request->hasFile($fileKey)) {
                    $data['steps'][$index]['image_file'] = $request->file($fileKey);
                }
            }
        }

        $recipe = $this->recipeService->updateRecipe($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil diperbarui!',
            'data' => new RecipeResource($recipe),
            'redirect' => route('recipes.index')
        ]);
    }
}
