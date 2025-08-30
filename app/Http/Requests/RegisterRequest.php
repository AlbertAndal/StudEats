<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'age' => ['nullable', 'integer', 'min:13', 'max:120'],
            'daily_budget' => ['nullable', 'numeric', 'min:100', 'max:1000'],
            'dietary_preferences' => ['nullable', 'array'],
            'dietary_preferences.*' => ['string', 'in:vegetarian,vegan,pescatarian,gluten_free,dairy_free,low_carb,high_protein'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'activity_level' => ['nullable', 'string', 'in:sedentary,lightly_active,moderately_active,very_active'],
            'height' => ['nullable', 'numeric', 'min:100', 'max:300'],
            'height_unit' => ['nullable', 'string', 'in:cm,ft'],
            'weight' => ['nullable', 'numeric', 'min:30', 'max:500'],
            'weight_unit' => ['nullable', 'string', 'in:kg,lbs'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'age.integer' => 'Age must be a valid number.',
            'age.min' => 'Age must be at least 13 years old.',
            'age.max' => 'Age cannot exceed 120 years.',
            'daily_budget.numeric' => 'Daily budget must be a valid number.',
            'daily_budget.min' => 'Daily budget must be at least ₱100.',
            'daily_budget.max' => 'Daily budget cannot exceed ₱1000.',
            'gender.in' => 'Please select a valid gender option.',
            'activity_level.in' => 'Please select a valid activity level.',
            'height.numeric' => 'Height must be a valid number.',
            'height.min' => 'Height must be at least 100.',
            'height.max' => 'Height cannot exceed 300.',
            'weight.numeric' => 'Weight must be a valid number.',
            'weight.min' => 'Weight must be at least 30.',
            'weight.max' => 'Weight cannot exceed 500.',
        ];
    }
}
