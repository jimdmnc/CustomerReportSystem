<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Responsibility: find customers by name or code.
 * Same tables/joins as the invoice query (custaddr <-> inv_hdr on cust_num + cust_seq),
 * so only customers that actually have invoices are returned.
 */
class CustomerRepository
{
    /** @return array<int, object> */
    public function search(string $term, int $limit = 15): array
    {
        $limit = max(1, min($limit, 50));
        $like  = '%' . addcslashes($term, '\\%_[') . '%';

        $sql = "
            SELECT TOP ({$limit})
                   ca.cust_num,
                   ca.cust_seq,
                   ca.name AS customer_name
            FROM dbo.custaddr ca
            WHERE (ca.name LIKE ? ESCAPE '\\' OR ca.cust_num LIKE ? ESCAPE '\\')
              AND EXISTS (
                    SELECT 1
                    FROM dbo.inv_hdr h
                    WHERE h.cust_num = ca.cust_num
                      AND h.cust_seq = ca.cust_seq
              )
            ORDER BY ca.name, ca.cust_num, ca.cust_seq
        ";

        return DB::connection('sqlsrv')->select($sql, [$like, $like]);
    }
}