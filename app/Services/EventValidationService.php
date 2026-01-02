<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Event Validation Service
 * 
 * Centralizes all validation logic for events
 * Includes input canonicalization for security
 */
class EventValidationService
{
    protected InputSanitizationService $sanitizationService;

    public function __construct(InputSanitizationService $sanitizationService)
    {
        $this->sanitizationService = $sanitizationService;
    }

    /**
     * Validate event creation request with canonicalization
     */
    public function validateEventCreation(Request $request, bool $isAdmin = false): array
    {
        $today = Carbon::now('Asia/Kuala_Lumpur')->toDateString();

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => ['required', Rule::in(['incoming', 'open', 'closed', 'cancelled', 'completed'])],
            'image' => $isAdmin ? 'nullable|image|max:4096' : 'nullable|image|max:2048',
            'needs_facility' => 'nullable|boolean',
            'facility_id' => 'nullable|integer|exists:facilities,id',
            'facility_start_at' => [
                'nullable',
                'date',
                'after_or_equal:' . $today,
            ],
            'facility_end_at' => [
                'nullable',
                'date',
                'after_or_equal:facility_start_at',
            ],
        ];

        $messages = [
            'facility_start_at.after_or_equal' => 'Facility booking date must be today or later.',
            'facility_end_at.after_or_equal' => 'Facility end time must be after or equal to start time.',
        ];

        // Add ticket validation for admin
        if ($isAdmin) {
            $rules['tickets'] = 'required|array|min:1';
            $rules['tickets.*.ticket_name'] = 'required|string|max:255';
            $rules['tickets.*.price'] = 'required|numeric|min:0';
            $rules['tickets.*.total_quantity'] = 'required|integer|min:1';
            $rules['tickets.*.sales_start_at'] = 'nullable|date';
            $rules['tickets.*.sales_end_at'] = 'nullable|date|after_or_equal:tickets.*.sales_start_at';

            $messages['tickets.required'] = 'At least one ticket is required.';
            $messages['tickets.min'] = 'At least one ticket is required.';
            $messages['tickets.*.ticket_name.required'] = 'Ticket name is required.';
            $messages['tickets.*.price.required'] = 'Ticket price is required.';
            $messages['tickets.*.total_quantity.required'] = 'Total quantity is required.';
        }

        // Validate first
        $validated = $request->validate($rules, $messages);

        // SECURITY: Apply canonicalization to validated data
        $validated = $this->canonicalizeEventData($validated);

        return $validated;
    }

    /**
     * Validate event update request with canonicalization
     */
    public function validateEventUpdate(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => ['required', Rule::in(['incoming', 'open', 'closed', 'cancelled', 'completed'])],
            'image' => 'nullable|image|max:2048',
        ]);

        // SECURITY: Apply canonicalization to validated data
        $validated = $this->canonicalizeEventData($validated);

        return $validated;
    }

    /**
     * Validate ticket purchase request
     */
    public function validateTicketPurchase(Request $request): array
    {
        return $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);
    }

    /**
     * Canonicalize event data for security
     * 
     * This prevents attacks using:
     * - Different Unicode encodings
     * - Path traversal in file names
     * - Null byte injection
     * - Various encoding tricks
     */
    protected function canonicalizeEventData(array $data): array
    {
        // Canonicalize string fields
        $stringFields = ['name', 'description', 'location'];
        foreach ($stringFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = $this->sanitizationService->canonicalizeString($data[$field]);
            }
        }

        // Canonicalize ticket data if present
        if (isset($data['tickets']) && is_array($data['tickets'])) {
            foreach ($data['tickets'] as $index => $ticket) {
                if (isset($ticket['ticket_name'])) {
                    $data['tickets'][$index]['ticket_name'] = 
                        $this->sanitizationService->canonicalizeString($ticket['ticket_name']);
                }
                // Canonicalize numeric fields
                if (isset($ticket['price'])) {
                    $data['tickets'][$index]['price'] = 
                        $this->sanitizationService->canonicalizeNumeric($ticket['price'], 'decimal');
                }
                if (isset($ticket['total_quantity'])) {
                    $data['tickets'][$index]['total_quantity'] = 
                        $this->sanitizationService->canonicalizeNumeric($ticket['total_quantity'], 'integer');
                }
            }
        }

        return $data;
    }
}