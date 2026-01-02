<?php

namespace App\Http\Requests;

use App\Models\FacilityBooking;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', FacilityBooking::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Input Validation: Get current date in UTC+8 timezone
        $today = Carbon::now('Asia/Kuala_Lumpur')->toDateString();

        return [
            'event_id' => ['required', 'integer'],
            'facility_name' => ['required', 'string'],
            'facility_id' => ['required', 'exists:facilities,id'],
            // Input Validation: Ensure booking date is not in the past
            'start_at' => [
                'required',
                'date',
                'after_or_equal:' . $today,
            ],
            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ],
            'status' => ['sometimes', 'in:PENDING,APPROVED,REJECTED,CANCELLED'],
            'reject_reason' => ['nullable', 'string'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_at.after_or_equal' => 'Facility booking date must be today or later.',
            'end_at.after' => 'End time must be after start time.',
        ];
    }
}
