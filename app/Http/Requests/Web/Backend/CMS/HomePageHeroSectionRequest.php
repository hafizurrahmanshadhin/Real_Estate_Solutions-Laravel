<?php

namespace App\Http\Requests\Web\Backend\CMS;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HomePageHeroSectionRequest extends FormRequest {
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
            'titles'       => 'required|array|min:1',
            'titles.*'     => 'required|string|max:255',
            'content'      => 'required|string',
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
            'titles.required'   => 'At least one title is required.',
            'titles.array'      => 'Titles must be an array.',
            'titles.*.required' => 'Each title is required.',
            'titles.*.string'   => 'Each title must be a valid string.',
            'titles.*.max'      => 'Each title may not be greater than 255 characters.',
            'content.required'  => 'The content field is required.',
            'content.string'    => 'The content must be a valid string.',
            'image.image'       => 'The file must be an image.',
            'image.mimes'       => 'The image must be a file of type: jpg, jpeg, png, gif, webp.',
            'image.max'         => 'The image may not be greater than 20MB.',
        ];
    }
}
