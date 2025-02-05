<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mappers\TimeKeepingMapper\DTRSummaryMapper;
use App\Excel\DTRSummaryExport;
use Maatwebsite\Excel\Facades\Excel;

class DTRSummaryController extends Controller
{
    //
    public $mapper;
    public $excel;

    public function __construct(DTRSummaryMapper $mapper,DTRSummaryExport $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.timekeeping.dtr-summary.index');
    }

    public function periodList()
    {
        $result = $this->mapper->periodList();

        return response()->json($result);
    }

    public function download(Request $request)
    {
        // dd($request->period_id);
        $period_id = $request->period_id;
        $list = $this->mapper->deleteAndInsert($request->period_id,'non-confi');

        $employees = $this->mapper->listEmployees($request->period_id,'non-confi');

        $this->excel->setValues($employees);
        return Excel::download($this->excel,'DTR-Sumamry'.$period_id.'.xlsx');
        // return view('app.timekeeping.dtr-summary.web',['employees' => $employees]);
    }

    public function download_confi(Request $request)
    {
        // dd($request->period_id);
        $period_id = $request->period_id;

        $list = $this->mapper->deleteAndInsert($request->period_id,'confi');

        $employees = $this->mapper->listEmployees($request->period_id,'confi');

        $this->excel->setValues($employees);
        return Excel::download($this->excel,'DTR-Sumamry'.$period_id.'.xlsx');
        
    }
    

    public function compute(Request $request)
    {   
        $period_id = $request->period_id;

        $ids = $this->mapper->employeesToProcess($period_id);

        $ctr = $this->mapper->processIDS($ids,$period_id);

        echo "Processed record : ". $ctr;
    }


}
