<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OtherServiceOrderRequest extends FormRequest {
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
            'first_name'        => ['required', 'string', 'max:50'],
            'last_name'         => ['required', 'string', 'max:50'],
            'phone_number'      => ['required', 'string', 'max:25'],
            'email'             => ['required', 'email', 'max:100'],

            'other_services_id' => [
                'required', 'integer',
                Rule::exists('other_services', 'id')->where(fn($q) => $q->where('status', 'active')),
            ],

            'additional_info'   => ['nullable', 'string', 'max:1000'],
            'address'           => ['required', 'string', 'max:200'],
            'city'              => ['required', 'string', 'max:100'],
            'state'             => ['required', 'string', 'max:100'],
            'zip_code'          => ['required', 'string', 'max:15'],

            'footage_size_id'   => [
                'required', 'integer',
                Rule::exists('footage_sizes', 'id')->where(fn($q) => $q->where('status', 'active')),
            ],
        ];
    }
}
