<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendReportEmailRequest;
use App\Mail\CustomerAccountReportMail;
use App\Repositories\InvoiceRepository;
use App\Services\Reports\CustomerAccountPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Responsibility: build the PDF on the server and email it to the recipient. */
class SendReportEmailController extends Controller
{
    public function __invoke(
        SendReportEmailRequest $request,
        InvoiceRepository $invoices,
        CustomerAccountPdfService $pdf
    ): JsonResponse {
        $data = $request->validated();
        $from = $data['date_from'] ?? null;
        $to   = $data['date_to'] ?? null;

        $lines = $invoices->forCustomerName($data['customer_name'], $from, $to);

        if (empty($lines)) {
            return response()->json(['message' => 'No invoice records found for the selected customer and dates.'], 422);
        }

        $customerName = trim((string) $lines[0]->customer_name);
        $fileName     = $pdf->fileName($customerName, $from, $to);

        $message = trim((string) ($data['message'] ?? ''))
            ?: "Please find attached the Customer Account Report for {$customerName}.";

        try {
            Mail::to($data['recipient_email'])->send(new CustomerAccountReportMail(
                $data['subject'],
                $message,
                $customerName,
                $pdf->make($lines, $from, $to)->output(),
                $fileName,
            ));
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => 'The email could not be sent. Please check the mail settings and try again.'], 500);
        }

        return response()->json([
            'message'   => "Email sent to {$data['recipient_email']}.",
            'file_name' => $fileName,
        ]);
    }
}