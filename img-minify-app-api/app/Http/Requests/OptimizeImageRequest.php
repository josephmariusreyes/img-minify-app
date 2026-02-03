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
            'files'  => 'required|array|min:1|max:5',
            'files.*'=> 'file|image|mimes:jpg,jpeg,png|max:262144', // Max 256MB per file
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'files.required' => 'Please upload at least one image file.',
            'files.array' => 'The files must be submitted as an array.',
            'files.min' => 'Please upload at least one image file.',
            'files.max' => 'You can upload a maximum of 5 images at a time.',
            'files.*.file' => 'Each uploaded item must be a valid file.',
            'files.*.image' => 'Each file must be a valid image.',
            'files.*.mimes' => 'Only JPG, JPEG, and PNG file formats are allowed.',
            'files.*.max' => 'Each image file size must not exceed 256MB.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('files')) {
                $totalSize = 0;
                foreach ($this->file('files') as $file) {
                    $totalSize += $file->getSize();
                }

                // Check if total size exceeds 250MB (in bytes)
                $maxTotalSize = 250 * 1024 * 1024; // 250MB in bytes
                if ($totalSize > $maxTotalSize) {
                    $validator->errors()->add('files', 'The total file size must not exceed 250MB.');
                }
            }
        });
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
                'description' => 'Array of image files to optimize (max 5 images, total size must not exceed 250MB)',
                'example' => null,
            ],
            'files.*' => [
                'description' => 'Image file (JPG, JPEG, or PNG format only)',
                'example' => null,
            ],
        ];
    }
}
