<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptimizeImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files'  => 'required|array|min:1',
            'files.*'=> 'file|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Get the body parameters documentation for Scribe.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'files' => [
                'description' => 'Array of image files to optimize',
                'example' => null,
            ],
            'files.*' => [
                'description' => 'Image file (JPG, JPEG, or PNG format, max 2MB)',
                'example' => null,
            ],
        ];
    }
}
