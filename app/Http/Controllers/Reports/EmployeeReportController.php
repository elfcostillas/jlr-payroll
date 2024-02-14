<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\EmployeeRecords;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Excel\CustomEmployeeList;

class EmployeeReportController extends Controller
{
    //
    private $excel;
    private $emp_mapper;
    private $custom_excel;

    public function __construct(EmployeeRecords $excel,EmployeeMapper $emp_mapper,CustomEmployeeList $custom_excel)
    {
        $this->excel = $excel;
        $this->emp_mapper = $emp_mapper;
        $this->custom_excel = $custom_excel;

    }

    public function index(){

        $headers = $this->emp_mapper->headers();

        return view('app.reports.employee-reports.index',['headers' => $headers]);
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
            'location' => $request->input('location'),
            'division' => $request->input('division'),
            'department' => $request->input('department'),
        ];
        
        $result = $this->emp_mapper->generateWeeklyEmployeeQR($filter);

        $pdf = PDF::loadView('app.reports.employee-reports.employee-quickreports',['data'=> $result])->setPaper('A4','portrait');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(40, 812, date_format(now(),"m/d/Y H:i:s"), null, 10, array(0, 0, 0));
        $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->stream('EmployeeQuickReports.pdf'); 
    }

    public function includeHeader(Request $request)
    {
        $result = $this->emp_mapper->include($request->header_id);
    }

    public function removeHeader(Request $request)
    {
        $result = $this->emp_mapper->remove($request->header_id);
    }

    public function getHeader(Request $request)
    {
        $result = $this->emp_mapper->getHeader();

        return response()->json($result);
    }

    public function customReport()
    {
        $header = $this->emp_mapper->getHeader();

        $filter = [
            'division' =>  null,
            'department'=> null
        ];

       

        $result = $this->emp_mapper->customReport();

        $this->custom_excel->setValues($header,$result);

        return Excel::download($this->custom_excel,'EmployeeData.xlsx');
   
        // return view('app.reports.employee-reports.custom-report',['headers' => $header, 'data' => $result]);
        
    }



    
}
