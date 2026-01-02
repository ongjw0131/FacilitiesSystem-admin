<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;

class FacilityStoreRequest extends FormRequest
{
    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Facility::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'venue_prefix' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'number_of_venues' => ['required', 'integer', 'min:1', 'max:100'],
            'type' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'facility_image' => ['nullable', 'file', 'image', 'max:2048'],
        ];
    }

    /**
     * After validation hook to enforce magic-byte (file header) validation.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $file = $this->file('facility_image');

            if (!$file) {
                return;
            }

            if (!$file->isValid()) {
                $validator->errors()->add('facility_image', 'Invalid image file type.');
                return;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file->getPathname()) ?: null;

            if (!$mime || !in_array($mime, self::ALLOWED_IMAGE_MIMES, true)) {
                $validator->errors()->add('facility_image', 'Invalid image file type.');
            }
        });
    }
}
