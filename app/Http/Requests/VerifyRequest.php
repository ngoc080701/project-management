<?php

namespace App\Http\Requests;


class VerifyRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        dd($this->user());
        return [
            'id' => ['required', 'in:' . $this->user()->getKey()],
            'hash' => ['required', 'in:' . sha1($this->user()->getEmailForVerification())],
            ];
    }
}
