<?php

namespace App\Http\Requests\Api\Payment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest {
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
            'contact.first_name'               => ['required', 'string', 'max:50'],
            'contact.last_name'                => ['required', 'string', 'max:50'],
            'contact.email'                    => ['required', 'email', 'max:100'],
            'contact.phone_number'             => ['required', 'string', 'max:25'],
            'contact.message'                  => ['nullable', 'string', 'max:1000'],
            'contact.is_agreed_privacy_policy' => ['accepted'],

            'property.address'                 => ['required', 'string', 'max:200'],
            'property.city'                    => ['required', 'string', 'max:100'],
            'property.state'                   => ['required', 'string', 'max:100'],
            'property.zip_code'                => ['required', 'string', 'max:15'],
            'property.property_type'           => ['required', 'string', 'max:100'],
            'property.footage_size_id'         => ['required', 'integer', Rule::exists('footage_sizes', 'id')->where(fn($q) => $q->where('status', 'active'))],

            'appointment.date'                 => ['required', 'date'],
            'appointment.time'                 => ['required', 'date_format:H:i'],

            'items'                            => ['required', 'array', 'min:1'],
            'items.*.type'                     => ['required', 'in:service,addon'],
            'items.*.id'                       => ['required', 'integer'],
            'items.*.quantity'                 => ['required', 'integer', 'min:1'],
            'items.*.unit_price'               => ['required', 'numeric', 'min:0'],

            'success_url'                      => ['required', 'url'],
            'cancel_url'                       => ['required', 'url'],
        ];
    }
}
