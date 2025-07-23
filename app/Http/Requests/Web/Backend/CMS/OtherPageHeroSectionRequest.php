<?php

namespace App\Http\Requests\Web\Backend\CMS;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OtherPageHeroSectionRequest extends FormRequest {
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
            'title'        => 'nullable|string|max:255',
            'content'      => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:20480',
            'remove_image' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'title.required'   => 'The title field is required.',
            'title.string'     => 'The title must be a valid string.',
            'title.max'        => 'The title may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'content.string'   => 'The content must be a valid string.',
            'image.image'      => 'The file must be an image.',
            'image.mimes'      => 'The image must be a file of type: jpg, jpeg, png, gif, webp.',
            'image.max'        => 'The image may not be greater than 20MB.',
        ];
    }
}
