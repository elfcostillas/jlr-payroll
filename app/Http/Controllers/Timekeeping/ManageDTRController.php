<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class ManageDTRController extends Controller
{
    //
    private $mapper;

    public function __construct(DailyTimeRecordMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        
        return view('app.timekeeping.manage-dtr.index');

    }
    public function prepareDTR(Request $request)
    {
        $result = $this->mapper->prepDTRbyPeriod($request->period_id,'semi');

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

        $result = $this->mapper->empWithDTR($request->period_id,$filter,'semi');
        return response()->json($result);
    }

    public function getEmployeeRawLogs(Request $request)
    {
        // $biometric_id = $request->input('biometric_id');
        // $period_id = $request->input('period_id');

        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getRawLogs($biometric_id,$period_id,'semi');
        return view('app.timekeeping.manage-dtr-weekly.raw-logs',['logs' => $result]);
        //return response()->json($result);
    }

    public function getSemiDTR(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->getSemiDTR($biometric_id,$period_id);
        return response()->json($result);
    }
    public function getSchedules()
    {
        $result = $this->mapper->getSchedules();
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
       
        $dtr = $this->mapper->getSemiDTR($biometric_id,$period_id);
        $this->mapper->mapRawLogs2($dtr);
        
        return response()->json(true);
    }

    public function computeLogs(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $dtr = $this->mapper->getSemiDTRforComputation($biometric_id,$period_id);

        $this->mapper->computeLogs($dtr,'semi');

        return response()->json(true);
    }

    public function print(Request $request)
    {
        $period_id = $request->period_id;
        $result = $this->mapper->getEmployeeForPrint($period_id,'semi');
       
        $pdf = PDF::loadView('app.timekeeping.manage-dtr.print',['employees' => $result])->setPaper('A4','portrait');

        //$pdf->output();
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream('JLR-DTR-Print.pdf'); 
        //return view('app.timekeeping.manage-dtr.print',['employees' => $result]);
    }
    
}
