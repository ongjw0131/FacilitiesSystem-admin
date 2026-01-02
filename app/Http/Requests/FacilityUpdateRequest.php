<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;

class FacilityUpdateRequest extends FormRequest
{
    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('facility')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'string', 'max:100'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'venue_id' => ['sometimes', 'string', 'max:50', 'unique:facilities,venue_id,' . (optional($this->route('facility'))->id)],
            'number_of_venues' => ['sometimes', 'integer', 'min:1', 'max:100'],
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
