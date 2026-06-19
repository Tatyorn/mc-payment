<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'currency' => ['required', 'string', 'size:3', 'alpha:ascii'],
            'role' => ['required', 'string', Rule::in(UserRole::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['role' => UserRole::EMPLOYEE->value]);
    }
}
