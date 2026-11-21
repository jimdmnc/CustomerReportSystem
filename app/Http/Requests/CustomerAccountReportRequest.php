<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAccountReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_number' => [
                'required',
                'string',
                'max:20',
            ],

            'customer_sequence' => [
                'nullable',
                'string',
                'max:10',
            ],

            'date_from' => [
                'required',
                'date_format:Y-m-d',
            ],

            'date_to' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:date_from',
            ],
        ];
    }
}