<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Reports\LeaveReportsMapper;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\LeavesExport;
use App\Excel\LeaveSummaryExport;
use App\Excel\LeaveByEmployee;
use App\Excel\LeavesByPayType;
use Carbon\Carbon;

class LeaveReportsController extends Controller
{
    //
    private $mapper;
    private $excel;
    private $summary;
    private $byEmployee;
    private $byPayType;

    public function __construct(LeaveReportsMapper $mapper,LeavesExport $excel,LeaveSummaryExport $summary,LeaveByEmployee $byEmployee,LeavesByPayType $byPayType)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
        $this->summary = $summary;
        $this->byEmployee = $byEmployee;
        $this->byPayType = $byPayType;
    }

    public function index()
    {
        return view('app.reports.leave-reports.index');
    }

    public function getLeavesFromTo(Request $request)
    {
      
        $from = $request->from;
        $to = $request->to;

        $result = $this->mapper->getLeavesfromRange($from,$to);

        $this->excel->setValues($result);
        return Excel::download($this->excel,'EmployeeLeaves.xlsx');
        //return view('app.reports.leave-reports.excel',['result' => $result]);
    }

    public function getLeavesByPayType(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

        $result = $this->mapper->getLeavesByPayType($from,$to);

        $this->byPayType->setValues($result);
        return Excel::download($this->byPayType,'EmployeeLeavesByPayType.xlsx');
    }

    public function getLeavesFromToWeb(Request $request)
    {
      
        $from = $request->from;
        $to = $request->to;

        $result = $this->mapper->getLeavesfromRange($from,$to);

        return view('app.reports.leave-reports.excel',['data' => $result]);
    }

    public function getLeaveSumamry(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
       
        $result = $this->mapper->getLeavesSummary($from,$to);
        
        $this->summary->setValues($result);
        return Excel::download($this->summary,'EmployeeLeavesSummary.xlsx');

        // return view('app.reports.leave-reports.summary-excel', [
        // 	'data' => $result
        // ]);
    }

    public function getLeaveByEmployee(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $result = $this->mapper->getLeaveSummaryByEmployee($from,$to,'nonconfi');
        
        $this->byEmployee->setValues($result);
        return Excel::download($this->byEmployee,'EmployeeLeavesByEmployeeSummary.xlsx');

        // return view('app.reports.leave-reports.leave-employee-excel', [
        // 	'data' => $result
        // ]);
    }

    public function getLeaveByEmployeeConfi(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $result = $this->mapper->getLeaveSummaryByEmployee($from,$to,'confi');
        
        $this->byEmployee->setValues($result);
        return Excel::download($this->byEmployee,'EmployeeLeavesByEmployeeSummary.xlsx');

        // return view('app.reports.leave-reports.leave-employee-excel', [
        // 	'data' => $result
        // ]);
    }

    public function viewKPI(Request $request)
    {
        $month[1] = 'January';
        $month[2] = 'February';
        $month[3] = 'March';
        $month[4] = 'April';
        $month[5] = 'May';
        $month[6] = 'June';
        $month[7] = 'July';
        $month[8] = 'August';
        $month[9] = 'September';
        $month[10] = 'October';
        $month[11] = 'November';
        $month[12] = 'December';

        $tableData = array();
      
        if($request->from == 'null' || $request->to == 'null')
        {
            
        }else{
            $from = Carbon::createFromFormat('Y-m-d',$request->from);
            $to = Carbon::createFromFormat('Y-m-d',$request->to);

            $index = (int) $from->month;
            $limit = (int) $to->month;

            $divisions = $this->mapper->getDivisions2($from,$to);

            for($i = $index;$i<=$limit; $i++)
            {
                $start = date('Y-m-d',strtotime($from->year.'-'.$i.'-01'));
                $end = date('Y-m-t',strtotime($start));

                //dd($start,$end)

                $data = $this->mapper->getData($start,$end,$from,$to);

                foreach($data as $row) {
                   
                    $tableData[$row->biometric_id][$i]['sl_count'] = $row->sl_count;
                    $tableData[$row->biometric_id][$i]['vl_count'] = $row->vl_count;
                    $tableData[$row->biometric_id][$i]['el_count'] = $row->el_count;
                    $tableData[$row->biometric_id][$i]['ut_count'] = $row->ut_count;

                    $tableData[$row->biometric_id][$i]['bl_count'] = $row->bl_count;
                    $tableData[$row->biometric_id][$i]['mp_count'] = $row->mp_count;
                    $tableData[$row->biometric_id][$i]['o_count'] = $row->o_count;
                    $tableData[$row->biometric_id][$i]['svl_count'] = $row->svl_count;
                    $tableData[$row->biometric_id][$i]['late_count'] = $row->late_count;
                    $tableData[$row->biometric_id][$i]['in_minutes'] = $row->in_minutes;

                    $tableData[$row->biometric_id][$i]['awol_count'] = $row->awol_count;

                 
                }
            }

          
            
        }

        return view('app.reports.leave-reports.kpi-web',['divisions' => $divisions,'month' => $month,'tableData' => $tableData,'index'=>$index,'limit'=>$limit,'year'=>$from->year]);
    }

    public function leaveOnDate(Request $request)
    {
        $date = $request->date;

        $result = $this->mapper->leaveOnDate($date);

        return view('app.reports.leave-reports.leave-by-date',['result' => $result]);
    }


}


/*
  +"biometric_id": "19"
  +"sl_count": "0"
  +"vl_count": "0"
  +"el_count": "0"
  +"ut_count": "0"
  +"bl_count": "1"
  +"mp_count": "0"
  +"o_count": "0"
  +"svl_count": "0"

*/