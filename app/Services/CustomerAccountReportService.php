<?php

namespace App\Services;

use App\Contracts\CustomerAccountReportInterface;
use LogicException;

class CustomerAccountReportService implements CustomerAccountReportInterface
{
    public function generateReport(
        string $customerNumber,
        ?string $customerSequence,
        string $dateFrom,
        string $dateTo
    ): array {
        // TODO: Implement customer account report queries.

        throw new LogicException(
            'Customer account report generation is not implemented yet.'
        );
    }
}