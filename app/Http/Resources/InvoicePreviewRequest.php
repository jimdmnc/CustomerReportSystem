<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Responsibility: validate the preview filters (customer + optional date range). */
class InvoicePreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cust_num'  => ['required', 'string', 'max:30'],
            'cust_seq'  => ['required', 'integer', 'min:0'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ];
    }
}