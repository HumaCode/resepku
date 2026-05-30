<?php

namespace App\Http\Requests\Konten;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecipeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $recipeId = $this->route('recipe');

        return [
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('recipes', 'slug')->ignore($recipeId),
            ],
            'description' => ['required', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'difficulty' => ['required', 'string', 'in:mudah,sedang,sulit,expert'],
            'prep_time' => ['required', 'integer', 'min:0'],
            'cook_time' => ['required', 'integer', 'min:0'],
            'servings' => ['required', 'integer', 'min:1'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'protein' => ['nullable', 'integer', 'min:0'],
            'fat' => ['nullable', 'integer', 'min:0'],
            'carbs' => ['nullable', 'integer', 'min:0'],
            'fiber' => ['nullable', 'integer', 'min:0'],
            'sugar' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'string', 'in:0,1'],
            'enable_comments' => ['nullable', 'string', 'in:0,1'],
            'enable_ratings' => ['nullable', 'string', 'in:0,1'],
            'status' => ['required', 'string', 'in:draft,published,pending,rejected'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'cover' => ['nullable', 'image', 'max:5120'], // 5MB max
            
            // Dynamic inputs
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.name' => ['required', 'string', 'max:255'],
            'ingredients.*.amount' => ['required', 'string', 'max:255'],
            'ingredients.*.unit' => ['required', 'string', 'max:255'],
            
            'steps' => ['nullable', 'array'],
            'steps.*.step_number' => ['required', 'integer', 'min:1'],
            'steps.*.description' => ['required', 'string'],
            'steps.*.image_file' => ['nullable', 'image', 'max:3072'], // 3MB max
            
            'videos' => ['nullable', 'array'],
            'videos.*.video_provider' => ['required', 'string', 'in:youtube,instagram,tiktok'],
            'videos.*.video_url' => ['required', 'url'],
            
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'exists:tags,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('videos')) {
            $videos = $this->input('videos');
            if (is_array($videos)) {
                foreach ($videos as $index => $video) {
                    if (isset($video['video_url'])) {
                        $url = trim($video['video_url']);
                        if (str_contains($url, '<blockquote') || str_contains($url, '<iframe') || str_contains($url, '<script')) {
                            // Extract URL from TikTok embed
                            if (preg_match('/cite=["\'](https?:\/\/[^"\']+)["\']/i', $url, $matches)) {
                                $videos[$index]['video_url'] = $matches[1];
                            }
                            // Extract URL from Instagram embed
                            elseif (preg_match('/data-instgrm-permalink=["\'](https?:\/\/[^"\']+)["\']/i', $url, $matches)) {
                                $videos[$index]['video_url'] = $matches[1];
                            }
                            // Extract URL from general iframe src
                            elseif (preg_match('/src=["\']([^"\']+)["\']/i', $url, $matches)) {
                                $src = $matches[1];
                                if (str_starts_with($src, '//')) {
                                    $src = 'https:' . $src;
                                }
                                $videos[$index]['video_url'] = $src;
                            }
                            // Fallback to any href
                            elseif (preg_match('/href=["\'](https?:\/\/[^"\']+)["\']/i', $url, $matches)) {
                                $videos[$index]['video_url'] = $matches[1];
                            }
                        }
                    }
                }
                $this->merge(['custom_videos_sanitized' => true, 'videos' => $videos]);
            }
        }
    }
}
