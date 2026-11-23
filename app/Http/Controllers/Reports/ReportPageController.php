<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

/** Responsibility: render the "Generate Reports" page. No data loading. */
class ReportPageController extends Controller
{
    public function __invoke()
    {
        return view('pages.generate-reports');
    }
}