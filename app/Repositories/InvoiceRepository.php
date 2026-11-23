<?php

namespace App\Repositories;

use App\Repositories\Concerns\BuildsAddressSql;
use Illuminate\Support\Facades\DB;

/** Responsibility: read invoice header + line rows from SQL Server. */
class InvoiceRepository
{
    use BuildsAddressSql;

    /** @return array<int, object> */
    public function forCustomerName(string $customerName, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $address  = $this->addressSql('ca');
        $bindings = [$customerName];
        $filters  = '';

        if ($dateFrom) {
            $filters   .= ' AND h.inv_date >= CAST(? AS date)';
            $bindings[] = $dateFrom;
        }

        if ($dateTo) {
            $filters   .= ' AND h.inv_date < DATEADD(day, 1, CAST(? AS date))';
            $bindings[] = $dateTo;
        }

        $sql = "
            SELECT
                h.inv_num,
                h.inv_seq,
                ISNULL(li.inv_line, 0) AS inv_line,
                h.inv_date,
                h.ship_date,
                h.cust_num,
                h.cust_seq,
                ca.name AS customer_name,
                {$address} AS address,
                ca.curr_code,
                ca.credit_limit,
                COALESCE(li.co_num, h.co_num) AS co_num,
                COALESCE(NULLIF(LTRIM(RTRIM(li.cust_po)), ''), NULLIF(LTRIM(RTRIM(h.cust_po)), '')) AS cust_po,
                li.item,
                li.qty_invoiced,
                COALESCE(li.price, h.price, 0) AS unit_price,
                COALESCE(NULLIF(li.cost, 0), h.cost, 0) AS unit_cost,
                CAST(ISNULL(li.qty_invoiced, 0) * ISNULL(li.price, h.price) AS DECIMAL(28, 8)) AS line_amount,
                COALESCE(NULLIF(LTRIM(RTRIM(li.do_num)), ''), NULLIF(LTRIM(RTRIM(h.do_num)), '')) AS do_num
            FROM dbo.inv_hdr h
            LEFT JOIN dbo.inv_item li
                   ON li.inv_num = h.inv_num
                  AND li.inv_seq = h.inv_seq
            LEFT JOIN dbo.custaddr ca
                   ON ca.cust_num = h.cust_num
                  AND ca.cust_seq = h.cust_seq
            WHERE ca.name = ?
              {$filters}
            ORDER BY h.inv_date DESC, h.inv_num DESC, li.inv_line ASC
        ";

        return DB::connection('sqlsrv')->select($sql, $bindings);
    }
}