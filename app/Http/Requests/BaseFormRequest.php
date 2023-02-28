<?php

namespace App\Http\Requests;

use App\Enum\ResponseCodeConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\BaseResult;

/**
 * Class BaseFormRequest
 *
 * @package Modules\Base\Http\Requests
 */
class BaseFormRequest extends FormRequest
{
    use BaseResult;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // TODO: code override something to here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // TODO: code override something to here
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        // Check is api system
        throw (new \Illuminate\Http\Exceptions\HttpResponseException(
            $this->unprocessableEntityResult(
                ResponseCodeConst::CODE_FAILED_VALIDATION,
                __('base::messages.error.failed_validation'),
                'ValidationException',
                (new \Illuminate\Validation\ValidationException($validator))->errors()
            )
        ));
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization(): void
    {
        // Check is api system
        throw (new \Illuminate\Http\Exceptions\HttpResponseException(
            $this->unauthorizedResult(
                ResponseCodeConst::CODE_UNAUTHORIZED,
                __('base::messages.error.unauthorized'),
                'AuthorizationException'
            )
        )
        );
    }

    /**
     * Merge Default data to Input request data after passed validation
     *
     * @param array $default set default input data
     */
    protected function mergeDefaultDataAfterPassedValidation(array $default = []): void
    {
        $input = $this->validator->getData();

        // set default value to InputSource (all())
        $this->merge($default);
        // set default value to validated
        $this->validator->setData(array_merge($input, $default));
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    protected function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
