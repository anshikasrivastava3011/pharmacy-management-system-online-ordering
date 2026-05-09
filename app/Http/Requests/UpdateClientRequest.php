<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user_id)],
            'gender' => ['required', 'in:Male,Female'],
            'date_of_birth' => ['required', 'date'],
            'avatar_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'phone' => ['required', 'digits:10'],
            'email_verified_at' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
    }

    public function messages(): array
    {
        return [
            'phone.digits' => 'The Phone Number must contain 10 digits',
            'avatar_image.mimes' => 'The image must be jpg, jpeg, or png only',
            'avatar_image.max' => 'The image must not be greater than 5 MB'
        ];
    }
}