<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Responsibility: validate the preview filters (customer name + optional date range on inv_date). */
class InvoicePreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:150'],
            'date_from'     => ['nullable', 'date_format:Y-m-d'],
            'date_to'       => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ];
    }
}