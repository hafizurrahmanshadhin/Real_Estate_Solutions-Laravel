<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactUsRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:contact_us,email',
            'phone_number' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[\+]?[1-9][\d\s\-\(\)]{8,18}$/',
                'unique:contact_us,phone_number',
            ],
            'message'      => 'required|string|max:2000',
            'is_agree'     => 'required|boolean|accepted',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'first_name.required'   => 'First name is required.',
            'first_name.string'     => 'First name must be a valid string.',
            'first_name.max'        => 'First name may not be greater than 255 characters.',

            'last_name.required'    => 'Last name is required.',
            'last_name.string'      => 'Last name must be a valid string.',
            'last_name.max'         => 'Last name may not be greater than 255 characters.',

            'email.required'        => 'Email address is required.',
            'email.email'           => 'Please provide a valid email address.',
            'email.max'             => 'Email may not be greater than 255 characters.',
            'email.unique'          => 'This email has already been used to submit a contact form.',

            'phone_number.required' => 'Phone number is required.',
            'phone_number.string'   => 'Phone number must be a valid string.',
            'phone_number.min'      => 'Phone number must be at least 10 digits.',
            'phone_number.max'      => 'Phone number may not be greater than 20 characters.',
            'phone_number.regex'    => 'Please provide a valid phone number (e.g., +1 (555) 123-4567, +1234567890, or 123-456-7890).',
            'phone_number.unique'   => 'This phone number has already been used to submit a contact form.',

            'message.required'      => 'Message is required.',
            'message.string'        => 'Message must be a valid string.',
            'message.max'           => 'Message may not be greater than 2000 characters.',

            'is_agree.required'     => 'You must agree to the terms and conditions.',
            'is_agree.boolean'      => 'Agreement field must be true or false.',
            'is_agree.accepted'     => 'You must agree to the terms and conditions to submit the form.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array {
        return [
            'first_name'   => 'first name',
            'last_name'    => 'last name',
            'email'        => 'email address',
            'phone_number' => 'phone number',
            'message'      => 'message',
            'is_agree'     => 'terms agreement',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void {
        throw new HttpResponseException(
            response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'code'    => 422,
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
