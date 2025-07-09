<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;

class ManageDTRConfiController extends Controller
{
    //
    private $mapper;

    public function __construct(DailyTimeRecordMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.manage-dtr-confi.index');
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

        $result = $this->mapper->empWithDTRConfi($request->period_id,$filter,'semi');
        return response()->json($result);
    }

    public function getEmployeeRawLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getRawLogs($biometric_id,$period_id,'semi');
        return view('app.timekeeping.manage-dtr-weekly.raw-logs',['logs' => $result]);
        
    }

    public function getSemiDTR(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getSemiDTR($biometric_id,$period_id);
        return response()->json($result);
    }

    public function drawLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;
       
        $dtr = $this->mapper->getSemiDTR($biometric_id,$period_id);
        $this->mapper->mapRawLogs2($dtr);
        
        return response()->json(true);
    }

    public function computeLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $dtr = $this->mapper->putLeavesUT($biometric_id,$period_id);

        $dtr = $this->mapper->getSemiDTRforComputation($biometric_id,$period_id);

        $this->mapper->computeLogs($dtr,'semi');

        return response()->json(true);
    }

    public function clearLogs(Request $request)
    {   
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->clearLogs($biometric_id,$period_id);

        return response()->json($result);
    }

    public function updateDTR(Request $request)
    {
        $logs = $request->models;
        foreach($logs as $log){

            var_dump($log);
           
            $result = $this->mapper->updateValid($log);
            if(is_object($result)){
                return response()->json($result)->setStatusCode(500, 'Error');
            }
           
        }
        return response()->json($result);
    }
}
