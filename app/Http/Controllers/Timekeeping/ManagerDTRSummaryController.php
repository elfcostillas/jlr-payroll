<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\DTRSummaryMapper;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use Illuminate\Http\Request;

use App\Mappers\TimeKeepingMapper\ManagerDTRSummaryMapper;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ManageDTRSummaryExport;
use Illuminate\Support\Facades\DB;

class ManagerDTRSummaryController extends Controller
{
    public $period_obj;
    public $mapper;
    public $dtrSummaryMapper;
    public $excel;

    public function __construct(PayrollPeriodMapper $period,ManagerDTRSummaryMapper $mapper, DTRSummaryMapper $dtrSummaryMapper,ManageDTRSummaryExport $excel)
    {
        $this->period_obj = $period->currentPayrollPeriod();
        $this->mapper = $mapper;
        $this->dtrSummaryMapper = $dtrSummaryMapper;
        $this->excel = $excel;
        // $this->middleware('auth');
    }

    public function index() 
    {
       
        return view('app.timekeeping.manage-dtr-summary.index',['period_obj'=>$this->period_obj]);
    }

    public function employeeList(Request $request)
    {   

        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
            'emp_level' => $request->input('emp_level'),
            'pay_type' => $request->input('pay_type')
        ];

        $result = $this->mapper->employeeList($this->period_obj->id,$filter);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $data = $request->models;

        foreach($data as $line)
        {
            $result = $this->mapper->updateValid($line);
        }
        return response()->json(true);
    }

    public function recomputeDTR(Request $request)
    {
        // dd($request->biometric_id,$request->period_id);
        $ids = [];

        $ids = $this->dtrSummaryMapper->employeesToProcessUni($request->biometric_id,$request->period_id);
       
        $ctr = $this->dtrSummaryMapper->processConfiIDSV3($ids,$request->period_id);

        // $result = $this->dtrSummaryMapper->processConfiIDSV2($ids,$request->period_id);
        // return response()->json($result);

    }

    
    public function deleteDTR(Request $request)
    {
        // dd($request->biometric_id,$request->period_id);
        $obj = DB::table('edtr_totals')->where('biometric_id', $request->biometric_id)->where('period_id', $request->period_id)->first();
        
        if($obj)
        {
            DB::table('edtr_totals')->where('biometric_id', $request->biometric_id)->where('period_id', $request->period_id)->delete();
        }
    }

    public function downloadTotals(Request $request)
    {
        $result = $this->dtrSummaryMapper->totalsByLocation($this->period_obj);
        $this->excel->setValues($result);
        return Excel::download($this->excel,'DTR-Sumamry-Totals-'.$this->period_obj->id.'.xlsx');
        // return view('app.timekeeping.manage-dtr-summary.export',['result' => $result]);
    }
}
