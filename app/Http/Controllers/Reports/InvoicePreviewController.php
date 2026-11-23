<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoicePreviewRequest;
use App\Http\Resources\InvoiceLineResource;
use App\Repositories\InvoiceRepository;

/** Responsibility: return the invoice lines shown in the PDF Preview card. */
class InvoicePreviewController extends Controller
{
    public function __invoke(InvoicePreviewRequest $request, InvoiceRepository $invoices)
    {
        $data = $request->validated();

        $lines = $invoices->forCustomerName(
            $data['customer_name'],
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
        );

        return InvoiceLineResource::collection(collect($lines));
    }
}