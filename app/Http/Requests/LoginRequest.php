<?php

namespace App\Http\Requests;


class LoginRequest extends BaseFormRequest
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
        ];
    }
}
