<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\EmployeeRecords;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeReportController extends Controller
{
    //
    private $excel;
    private $emp_mapper;

    public function __construct(EmployeeRecords $excel,EmployeeMapper $emp_mapper)
    {
        $this->excel = $excel;
        $this->emp_mapper = $emp_mapper;

    }

    public function index(){
        return view('app.reports.employee-reports.index');
    }

    public function generate(Request $request)
    {
        
        $filter = [
            'division' =>  $request->input('division'),
            'department'=> $request->input('department'),
        ];
        
        $result = $this->emp_mapper->generateReport($filter);

        //return view('app.reports.employee-reports.employee-list',['data' => $result]);
        $this->excel->setValues($result,null);
        return Excel::download($this->excel,'EmployeeMasterData.xlsx');
    }

    public function generateWeekly(Request $request)
    {
        $filter = [
            // 'division' =>  $request->input('divison'),
            'location' =>  $request->input('division'),
            'division' =>  $request->input('location'),
            'department'=> $request->input('department'),
        ];

        $result = $this->emp_mapper->generateReportWeekly($filter);

        //return view('app.reports.employee-reports.employee-list',['data' => $result]);
        $this->excel->setValues($result,null);
        return Excel::download($this->excel,'EmployeeMasterData.xlsx');
    }

    public function printWeekly(Request $request)
    {
        $filter = [
            // 'division' =>  $request->input('divison'),
            'location' => $request->input('division'),
            'division' => $request->input('location'),
            'department' => $request->input('department'),
        ];
        

        dd($filter);

        // $pdf = PDF::loadView('app.payroll-transaction.payslip-weekly.dtr-summary-pdf',['data'=> $result,'headers'=>$headers,'label' => $label])->setPaper('A4','portrait');
        // $pdf->output();

        // $dom_pdf = $pdf->getDomPDF();
    
        // $canvas = $dom_pdf->get_canvas();
        // $canvas->page_text(40, 812, date_format(now(),"m/d/Y H:i:s"), null, 10, array(0, 0, 0));
        // $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        // return $pdf->stream('WeeklyReport.pdf'); 
    }


    
}
