<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Reports\LeaveReportsMapper;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\LeavesExport;

class LeaveReportsController extends Controller
{
    //
    private $mapper;
    private $excel;

    public function __construct(LeaveReportsMapper $mapper,LeavesExport $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
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
}
