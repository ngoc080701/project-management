<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() :array
    {
        return [
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'min:8', 'max:32'],
            'full_name' => ['required', new NameRule()],
            'dob' => ['nullable', 'before:now', 'date_format:Y-m-d'],
            'address' => ['nullable'],
            'phone_number' => ['nullable'],
            'gender' => ['nullable'],
        ];
    }
}
