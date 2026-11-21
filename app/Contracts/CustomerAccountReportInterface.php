<?php

namespace App\Contracts;

interface CustomerAccountReportInterface
{
    public function generateReport(
        string $customerNumber,
        ?string $customerSequence,
        string $dateFrom,
        string $dateTo
    ): array;
}