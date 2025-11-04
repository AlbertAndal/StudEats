<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $user = $this->user();
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'age' => 'nullable|integer|min:13|max:120',
            'gender' => 'nullable|string|in:male,female,other,prefer_not_to_say',
            'height' => 'nullable|numeric|min:100|max:250',
            'height_unit' => 'nullable|string|in:cm,ft',
            'weight' => 'nullable|numeric|min:30|max:300',
            'weight_unit' => 'nullable|string|in:kg,lbs',
            'activity_level' => 'nullable|string|in:sedentary,lightly_active,moderately_active,very_active,extremely_active',
            'daily_budget' => 'nullable|numeric|min:100|max:2000',
            'timezone' => 'nullable|string|max:255',
            'dietary_preferences' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Your full name is required.',
            'name.max' => 'Your name cannot be longer than 255 characters.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use by another account.',
            'age.integer' => 'Please enter a valid age.',
            'age.min' => 'You must be at least 13 years old to use StudEats.',
            'age.max' => 'Please enter a realistic age.',
            'gender.in' => 'Please select a valid gender option.',
            'height.numeric' => 'Please enter a valid height.',
            'height.min' => 'Height must be at least 100cm (3.3ft).',
            'height.max' => 'Height cannot exceed 250cm (8.2ft).',
            'height_unit.in' => 'Please select a valid height unit (cm or ft).',
            'weight.numeric' => 'Please enter a valid weight.',
            'weight.min' => 'Weight must be at least 30kg (66lbs).',
            'weight.max' => 'Weight cannot exceed 300kg (661lbs).',
            'weight_unit.in' => 'Please select a valid weight unit (kg or lbs).',
            'activity_level.in' => 'Please select a valid activity level.',
            'daily_budget.numeric' => 'Please enter a valid budget amount.',
            'daily_budget.min' => 'Daily budget must be at least ₱100.',
            'daily_budget.max' => 'Daily budget cannot exceed ₱2,000.',
            'timezone.max' => 'Timezone name is too long.',
            'dietary_preferences.string' => 'Dietary preferences must be text.',
            'dietary_preferences.max' => 'Dietary preferences cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'age' => 'age',
            'gender' => 'gender',
            'height' => 'height',
            'height_unit' => 'height unit',
            'weight' => 'weight',
            'weight_unit' => 'weight unit',
            'activity_level' => 'activity level',
            'daily_budget' => 'daily budget',
            'timezone' => 'timezone',
            'dietary_preferences' => 'dietary preferences',
        ];
    }
}
