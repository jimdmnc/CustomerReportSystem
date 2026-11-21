<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = (object) [
            'name'    => 'Printwell Inc.',
            'code'    => 'PI-SP',
            'email'   => 'Printwell@printwell.com',
            'address' => '38 Dansalan St. Mandaluyong City, Metro Manila, Philippines',
        ];

        $transactions = [
            (object) ['date' => 'Oct 03', 'doc_no' => 'SI-88120', 'debit' => 148500.00, 'credit' => 0.00,      'balance' => 148500.00],
            (object) ['date' => 'Oct 11', 'doc_no' => 'SI-88214', 'debit' => 62300.00,  'credit' => 0.00,      'balance' => 210800.00],
            (object) ['date' => 'Oct 18', 'doc_no' => 'OR-44091', 'debit' => 0.00,      'credit' => 148500.00, 'balance' => 62300.00],
            (object) ['date' => 'Oct 27', 'doc_no' => 'SI-88377', 'debit' => 95750.00,  'credit' => 0.00,      'balance' => 158050.00],
        ];

        $report = (object) [
            'title'      => 'Customers',
            'date_from'  => '2026-10-01',
            'date_to'    => '2026-10-31',
            'date_range' => 'Oct 1 – Oct 31, 2026',
            'file_name'  => 'PI_Customers.pdf',
            'file_size'  => '84 KB',
            'status'     => 'Pending',
            'total'      => 158050.00,
        ];

        return view('pages.customers', compact('customer', 'transactions', 'report'));
    }
}
