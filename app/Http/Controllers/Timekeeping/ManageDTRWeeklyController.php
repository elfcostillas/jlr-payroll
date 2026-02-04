<?php

namespace App\Http\Controllers\Timekeeping;

use App\Excel\WeeklyDTR;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Maatwebsite\Excel\Facades\Excel;

class ManageDTRWeeklyController extends Controller
{
    //
    private $mapper;
    private $excel;

    public function __construct(DailyTimeRecordMapper $mapper,WeeklyDTR $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
    }

    public function index()
    {
        
        return view('app.timekeeping.manage-dtr-weekly.index');

    }

    public function index_sub()
    {
        return view('app.timekeeping.manage-dtr-weekly.index-sub');
    }

    public function prepareDTR(Request $request)
    {
        $result = $this->mapper->prepDTRbyPeriod($request->period_id,'weekly');

        return response()->json($result);
    }

    public function getEmployeeList(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->empWithDTR($request->period_id,$filter,'weekly');
        return response()->json($result);
    }

    public function getEmployeeRawLogs(Request $request)
    {
        // $biometric_id = $request->input('biometric_id');
        // $period_id = $request->input('period_id');

        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getRawLogs($biometric_id,$period_id,'weekly');
        return view('app.timekeeping.manage-dtr-weekly.raw-logs',['logs' => $result]);
        //return response()->json($result);
    }

    public function getweeklyDTR(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getweeklyDTR($biometric_id,$period_id);
        return response()->json($result);
    }
    
    public function getSchedules()
    {
        $result = $this->mapper->getSchedules();
        return response()->json($result);
    }

    public function getSchedulesSat()
    {
        $result = $this->mapper->getSchedulesSat();
        return response()->json($result);
    }

    

    public function updateDTR(Request $request)
    {
        $logs = $request->models;
        foreach($logs as $log){
            $result = $this->mapper->updateValid($log);
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
           
        }
        return response()->json($result);
    }

    public function drawLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        //$rawlogs = $this->mapper->getRawLogs($biometric_id,$period_id);
       
        //$this->mapper->mapRawLogs($rawlogs);
       
        $dtr = $this->mapper->getweeklyDTR($biometric_id,$period_id);
        $this->mapper->mapRawLogs2($dtr);
        
        return response()->json(true);
    }

    public function computeLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        // $dtr = $this->mapper->putLeavesUT($biometric_id,$period_id);

        $dtr = $this->mapper->getWeeklyDTRforComputation($biometric_id,$period_id);

        $this->mapper->computeLogs($dtr,'weekly');

        return response()->json(true);
    }

    public function drawLogsM(Request $request)
    {
        
    }

    public function computeAll(Request $request)
    {

        $emp = $this->mapper->getAllEmptoCompute($request->period_id);
       
        
        foreach($emp as $e){
            //$e->biometric_id;
            //dd($request->period_id);

            $dtr = $this->mapper->getweeklyDTR($e->biometric_id,$request->period_id);
            $this->mapper->mapRawLogs2($dtr);

            $logs = $this->mapper->getWeeklyDTRforComputation($e->biometric_id,$request->period_id);
            if($logs && count($logs) >0){
                
                $this->mapper->computeLogs($logs,'weekly');

            }
            
        } 

        return response()->json(true);        
    }

    public function download($period_id)
    {
        $result = $this->mapper->downloadWeekly($period_id);

        $this->excel->setValues($result);
        return Excel::download($this->excel,'DTR-Sumamry'.$period_id.'.xlsx');
        // return view('app.timekeeping.manage-dtr-weekly.excel',['data' => $result ]);
    }

    public function downloadVite($period_id)
    {
        $result = $this->mapper->downloadWeeklyVite($period_id);

        $this->excel->setValues($result);
        return Excel::download($this->excel,'DTR-Sumamry'.$period_id.'.xlsx');
        // return view('app.timekeeping.manage-dtr-weekly.excel',['data' => $result ]);
    }
    
    
}
