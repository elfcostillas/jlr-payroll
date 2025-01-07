<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\Reports\SGContributionMapper;
use Illuminate\Http\Request;

class SGContributionsController extends Controller
{
    //
    private $mapper;

    public function __construct(SGContributionMapper $mapper)
    {
        $this->mapper = $mapper;
    }   

    public function index()
    {
        return view('app.reports.sg-contribution.index');
    }

    public function fy()
    {
        $result =  $this->mapper->yearList();

        return response()->json($result);
    }

    public function generate(Request $request)
    {
        // dd($request->year,$request->month);

        $months = array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        );

        $label = $months[$request->month] .' '. $request->year;

        $result = $this->mapper->generate($request->year,$request->month);

        return view('app.reports.sg-contribution.table',['locations' => $result, 'label' => $label]);
    }
}
