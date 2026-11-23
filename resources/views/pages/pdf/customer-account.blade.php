<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Account Report</title>
<style>
    /* dompdf does not run Tailwind: styles must be inline here. */
    @page { margin: 28px 30px; }
    body   { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; }
    h1     { font-size: 14px; margin: 0 0 6px; letter-spacing: 1px; text-transform: uppercase; }
    .meta  { width: 100%; margin-bottom: 14px; }
    .meta td { vertical-align: top; padding: 0; }
    .right { text-align: right; }
    .name  { font-size: 11px; font-weight: bold; }
    .muted { color: #6b7280; }
    table.lines { width: 100%; border-collapse: collapse; }
    table.lines th { text-align: left; font-weight: bold; color: #6b7280; border-bottom: 1px solid #d1d5db; padding: 5px 4px; }
    table.lines td { border-bottom: 1px solid #f3f4f6; padding: 4px; }
    table.lines tr { page-break-inside: avoid; }
    .num   { text-align: right; }
    .total td { border-top: 2px solid #9ca3af; border-bottom: none; font-weight: bold; padding-top: 7px; }
</style>
</head>
<body>

<table class="meta">
    <tr>
        <td>
            <h1>Customers of Printwell</h1>
            <div class="name">{{ $customerName }}</div>
            <div>Customer Code: {{ $customerCode }}</div>
            <div class="muted">{{ $customerAddress ?: '---' }}</div>
        </td>
        <td class="right muted">
            <div>{{ $period }}</div>
            <div>{{ count($rows) }} {{ \Illuminate\Support\Str::plural('line', count($rows)) }}</div>
            <div>Generated {{ $generatedAt }}</div>
        </td>
    </tr>
</table>

<table class="lines">
    <thead>
        <tr>
            <th>Inv. Date</th>
            <th>Invoice No.</th>
            <th>Line</th>
            <th>Cust. PO</th>
            <th>Item</th>
            <th>DO No.</th>
            <th class="num">Qty</th>
            <th class="num">Unit Price</th>
            <th class="num">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td>{{ $row['inv_date'] ? \Carbon\Carbon::parse($row['inv_date'])->format('M j, Y') : '' }}</td>
                <td>{{ $row['inv_num'] }}</td>
                <td>{{ $row['inv_line'] }}</td>
                <td>{{ $row['cust_po'] ?? '—' }}</td>
                <td>{{ $row['item'] ?? '—' }}</td>
                <td>{{ $row['do_num'] ?? '—' }}</td>
                <td class="num">{{ rtrim(rtrim(number_format($row['qty_invoiced'], 2), '0'), '.') }}</td>
                <td class="num">{{ number_format($row['unit_price'], 2) }}</td>
                <td class="num">{{ number_format($row['line_amount'], 2) }}</td>
            </tr>
        @endforeach
        <tr class="total">
            <td colspan="8" class="num">Total {{ $currency }}</td>
            <td class="num">{{ number_format($total, 2) }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>