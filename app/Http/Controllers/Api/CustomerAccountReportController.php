<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CustomerAccountReportInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAccountReportRequest;
use Illuminate\Http\JsonResponse;

class CustomerAccountReportController extends Controller
{
    public function __construct(
        private CustomerAccountReportInterface $reportService
    ) {}

    public function __invoke(
        CustomerAccountReportRequest $request
    ): JsonResponse {

        $validated = $request->validated();

        $report = $this->reportService->generateReport(
            $validated['customer_number'],
            $validated['customer_sequence'] ?? null,
            $validated['date_from'],
            $validated['date_to']
        );

        return response()->json([
            'data' => $report,
        ]);
    }
}