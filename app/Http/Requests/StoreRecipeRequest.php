<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'meal_id' => ['required', 'exists:meals,id'],
            'name' => ['required', 'string', 'max:150'],
            'cuisine_type' => ['required', 'string', 'max:80'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'cost' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'],
            'prep_time' => ['required', 'integer', 'min:1', 'max:1440'],
            'cook_time' => ['required', 'integer', 'min:1', 'max:1440'],
            'servings' => ['required', 'integer', 'min:1', 'max:100'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string', 'max:120'],
            'instructions' => ['required', 'string'],
            'calories' => ['required', 'integer', 'min:0', 'max:5000'],
            'protein' => ['required', 'numeric', 'min:0', 'max:500'],
            'carbs' => ['required', 'numeric', 'min:0', 'max:1000'],
            'fats' => ['required', 'numeric', 'min:0', 'max:500'],
            'fiber' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'sugar' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'sodium' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'difficulty.in' => 'The selected difficulty is invalid.',
            'ingredients.*.required' => 'Each ingredient must have a value.',
        ];
    }
}
