<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QPIPController extends Controller
{
    //
    public function index()
    {
        // $years = []; select distinct year(dtr_date) as dtr_year from edtr_detailed order by dtr_year asc;
        return view('app.reports.qpip.index');
    }
}
