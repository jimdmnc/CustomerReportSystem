<?php

namespace App\Repositories\Concerns;

/** Responsibility: the one SQL expression that joins addr##1..4, zip and country into one string. */
trait BuildsAddressSql
{
    protected function addressSql(string $alias = 'ca'): string
    {
        $parts = [];

        foreach (['addr##1', 'addr##2', 'addr##3', 'addr##4', 'zip', 'country'] as $column) {
            $parts[] = "ISNULL(', ' + NULLIF(LTRIM(RTRIM({$alias}.{$column})), ''), '')";
        }

        return 'STUFF(' . implode(' + ', $parts) . ", 1, 2, '')";
    }
}