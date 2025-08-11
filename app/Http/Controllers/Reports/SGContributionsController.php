<?php

namespace App\Http\Controllers\Reports;

use App\Excel\Contribution;
use App\Excel\ContributionSorted;
use App\Excel\CumulativeContribution;
use App\Http\Controllers\Controller;
use App\Mappers\Reports\SGContributionMapper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SGContributionsController extends Controller
{
    //
    private $mapper;
    private $cumulative;
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

    public function __construct(SGContributionMapper $mapper,CumulativeContribution $cumulative,Contribution $single,ContributionSorted $sorted )
    {
        $this->mapper = $mapper;
        $this->cumulative = $cumulative;
        $this->single = $single;
        $this->sorted = $sorted;
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

    public function export(Request $request)
    {
        $label = $this->months[$request->month] .' '. $request->year;

        $result = $this->mapper->generate($request->year,$request->month);

        $this->cumulative->setValues($result,$label);
        return Excel::download($this->cumulative,'Contribution'.$label.'.xlsx');
       
    }

    public function webByType(Request $request)
    {
        //dd($request->type); 1 SSS - 2 HDMF - 3 PHIC
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generate($request->year,$request->month);
        $type = $request->type;

        return view('app.reports.sg-contribution.table-by-type',['locations' => $result, 'label' => $label,'type' => $type]);
    }

    public function excelByType(Request $request)
    {
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generate($request->year,$request->month);
        $type = $request->type;

        $this->single->setValues($result,$label,$type,'sg');
        return Excel::download($this->single,'Contribution'.$label.'.xlsx');

    }

    public function excelByTypeS(Request $request)
    {

        // dd(123);
        $label = $this->months[$request->month] .' '. $request->year;
        $result = $this->mapper->generateS($request->year,$request->month);
        $type = $request->type;

        $this->sorted->setValues($result,$label,$type,'sg');
        return Excel::download($this->sorted,'Contribution'.$label.'.xlsx');

    }

    
}
