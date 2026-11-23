<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $customers = DB::table('dbo.custaddr')
            ->select('name', 'cust_num')
            ->where('cust_seq', 0)
            ->whereNotNull('name')
            ->whereRaw("LTRIM(RTRIM(name)) <> ''")
            ->whereRaw("LTRIM(RTRIM(name)) NOT LIKE '*%'") // Excludes names starting with *
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('cust_num', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('LTRIM(RTRIM(name)) ASC')
            ->paginate(15)
            ->withQueryString()
            ->through(function ($item) {
                $item->name = trim($item->name);
                $item->cust_num = trim($item->cust_num);
                return $item;
            });

        // Return JSON (including pagination links HTML) for live keystroke search
        if ($request->ajax()) {
            return response()->json([
                'data'  => $customers->items(),
                'links' => (string) $customers->links(),
            ]);
        }

        return view('pages.customers', compact('customers'));
    }
}
