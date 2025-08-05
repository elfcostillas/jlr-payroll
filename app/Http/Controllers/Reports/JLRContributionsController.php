<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\Reports\JLRContributionMapper;
use Illuminate\Http\Request;

class JLRContributionsController extends Controller
{
    //
    private $mapper;

    public $months = array( 
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

    public function __construct(JLRContributionMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.reports.jlr-contribution.index');
    }

    public function fy()
    {
        $result =  $this->mapper->yearList();

        return response()->json($result);
    }

    public function webByType(Request $request)
    {
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generate($request->year,$request->month);
        $type = $request->type;

        return view('app.reports.jlr-contribution.table-by-type',['locations' => $result, 'label' => $label,'type' => $type]);
    }

    public function excelByType(Request $request)
    {

    }

    public function excelByTypeS(Request $request)
    {

    }

}
