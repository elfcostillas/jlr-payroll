<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Excel\DTRExport;
use Maatwebsite\Excel\Facades\Excel;

class ManageDTRController extends Controller
{
    //
    private $mapper;
    private $excel;

    public function __construct(DailyTimeRecordMapper $mapper,DTRExport $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
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

    public function exportSemiDTR(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;
      
        $result = $this->mapper->getSemiDTRexp($period_id);

        $this->excel->setValues($result);
        return Excel::download($this->excel,'DTR'.$period_id.'.xlsx');

        //return view('app.timekeeping.manage-dtr.dtr-download',['data' => $result ]);
        //return response()->json($result);
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

        $dtr = $this->mapper->putLeavesUT($biometric_id,$period_id);

        $dtr = $this->mapper->getSemiDTRforComputation($biometric_id,$period_id);

        $this->mapper->computeLogs($dtr,'semi');

        return response()->json(true);
    }

    public function onetimebigtime(Request $request){
        /*
            SELECT DISTINCT biometric_id FROM edtr_raw 
            LEFT JOIN payroll_period ON edtr_raw.punch_date BETWEEN date_from AND date_to WHERE payroll_period.id = 1
        */
        //dd($request->period_id);
        $result = $this->mapper->onetimebigtime($request->period_id);
        $period_id = $request->period_id;
        foreach($result as $bio_id)
        {
        //    dd($bio_id);

             
            $dtr = $this->mapper->getSemiDTR($bio_id->biometric_id,$period_id);
            $this->mapper->mapRawLogs2($dtr);

            $dtr2 = $this->mapper->putLeavesUT($bio_id->biometric_id,$period_id);

            $dtr3 = $this->mapper->getSemiDTRforComputation($bio_id->biometric_id,$period_id);

            $this->mapper->computeLogs($dtr3,'semi');

        }

    }

    
    public function computeAllDTR(Request $request){
       
        $period_id = $request->input('period_id');

        $result = $this->mapper->onetimebigtime($period_id);
        foreach($result as $bio_id)
        {
            $dtr = $this->mapper->getSemiDTR($bio_id->biometric_id,$period_id);
            $this->mapper->mapRawLogs2($dtr);

            $dtr2 = $this->mapper->putLeavesUT($bio_id->biometric_id,$period_id);

            $dtr3 = $this->mapper->getSemiDTRforComputation($bio_id->biometric_id,$period_id);

            $this->mapper->computeLogs($dtr3,'semi');
        }

        return response()->json(true);
    }

    public function clearLogs(Request $request)
    {   
        $biometric_id = $request->biometric_id;
        $period_id = $request->period_id;

        $result = $this->mapper->clearLogs($biometric_id,$period_id);

        return response()->json($result);
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
        //return $pdf->download('JLR-DTR-Print.pdf'); 
        return $pdf->stream('JLR-DTR-Print.pdf'); 
        //return view('app.timekeeping.manage-dtr.print',['employees' => $result]);
    }

    public function iprint(Request $request)
    {
        $period_id = $request->period_id;
        $biometric_id = $request->biometric_id;

        $result = $this->mapper->getEmployeeForiPrint($period_id,$biometric_id,'semi');

        $pdf = PDF::loadView('app.timekeeping.manage-dtr.print',['employees' => $result])->setPaper('A4','portrait');

        //$pdf->output();
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        //return $pdf->download('JLR-DTR-Print.pdf'); 
        return $pdf->stream('JLR-DTR-Print.pdf'); 
    }

    public function scheduleSetter(Request $request)
    {
        
        $period_id = (int) $request->period_id;
        $result = $this->mapper->mapSchedtoDTR($period_id);

        foreach($result as $line){
            switch($line->wday)
            {
                case 'Mon' : 
                case 'Tue' : 
                case 'Wed' : 
                case 'Thu' : 
                case 'Fri' : 
                    
                        $qry = "UPDATE edtr SET schedule_id = $line->sched_mtwtf WHERE biometric_id = $line->biometric_id AND dtr_date = '$line->dtr_date';"; 
                    break;
                    
                case 'Sat' : 
                        $qry = "UPDATE edtr SET schedule_id = $line->sched_sat WHERE biometric_id = $line->biometric_id AND dtr_date = '$line->dtr_date';"; 
                    break;
            }
            //DB::statement()
            echo $qry.'<br>';
        }
    }
    
}

/*
  +"biometric_id": "4"
  +"schedule_id": "0"
  +"dtr_date": "2023-01-02"
  +"sched_mtwtf": "1"
  +"sched_sat": "6"
  +"wday": "Mon"

  SELECT time_in,time_out,(TIME_TO_SEC(time_out)-TIME_TO_SEC(time_in))/3600 FROM edtr INNER JOIN payroll_period ON edtr.dtr_date BETWEEN date_from AND date_to 

  */
