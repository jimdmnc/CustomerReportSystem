<?php

namespace App\Http\Requests;

/**
 * Responsibility: validate the "send report by email" form.
 * Reuses the customer + date rules from InvoicePreviewRequest and adds the email fields.
 */
class SendReportEmailRequest extends InvoicePreviewRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'recipient_email' => ['required', 'email', 'max:254'],
            'subject'         => ['required', 'string', 'max:150'],
            'message'         => ['nullable', 'string', 'max:5000'],
        ]);
    }
}