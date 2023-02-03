<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Reports\LeaveReportsMapper;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\LeavesExport;
use App\Excel\LeaveSummaryExport;
use App\Excel\LeaveByEmployee;

class LeaveReportsController extends Controller
{
    //
    private $mapper;
    private $excel;
    private $summary;
    private $byEmployee;

    public function __construct(LeaveReportsMapper $mapper,LeavesExport $excel,LeaveSummaryExport $summary,LeaveByEmployee $byEmployee)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
        $this->summary = $summary;
        $this->byEmployee = $byEmployee;
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

        $result = $this->mapper->getLeaveSummaryByEmployee($from,$to);
        
        $this->byEmployee->setValues($result);
        return Excel::download($this->byEmployee,'EmployeeLeavesByEmployeeSummary.xlsx');

        // return view('app.reports.leave-reports.leave-employee-excel', [
        // 	'data' => $result
        // ]);
    }
}
