<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\EmployeeRecords;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;

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
        ];
        
        $result = $this->emp_mapper->generateReport($filter);

        //return view('app.reports.employee-reports.employee-list',['data' => $result]);
        $this->excel->setValues($result,null);
        return Excel::download($this->excel,'EmployeeMasterData.xlsx');
    }

    public function generateWeekly(Request $request)
    {
        $filter = [
            'division' =>  $request->input('divison'),
        ];

        $result = $this->emp_mapper->generateReportWeekly($filter);

        //return view('app.reports.employee-reports.employee-list',['data' => $result]);
        $this->excel->setValues($result,null);
        return Excel::download($this->excel,'EmployeeMasterData.xlsx');
    }


    
}
