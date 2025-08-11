<?php

namespace App\Http\Controllers\Reports;

use App\Excel\Contribution;
use App\Excel\ContributionSorted;
use App\Http\Controllers\Controller;
use App\Mappers\Reports\JLRContributionMapper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JLRContributionsController extends Controller
{
    //
    private $mapper;
    private $single;
    private $sorted;

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

    public function __construct(JLRContributionMapper $mapper,Contribution $single,ContributionSorted $sorted)
    {
        $this->mapper = $mapper;
        $this->single = $single;
        $this->sorted = $sorted;
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
        $result = $this->mapper->generate($request->year,$request->month,$request->emp_level);
        $type = $request->type;

        return view('app.reports.jlr-contribution.table-by-type',['locations' => $result, 'label' => $label,'type' => $type]);
    }

    public function excelByType(Request $request)
    {
       
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generate($request->year,$request->month,$request->emp_level);
        $type = $request->type;

        $this->single->setValues($result,$label,$type,$request->emp_level);
        return Excel::download($this->single,'Contribution '.$request->emp_level.' '.$label.'.xlsx');

    }

    public function excelByTypeS(Request $request)
    {
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generateS($request->year,$request->month,$request->emp_level);
        $type = $request->type;

        $this->sorted->setValues($result,$label,$type);
        return Excel::download($this->sorted,'Contribution '.$request->emp_level.$label.'.xlsx');
    }

}
