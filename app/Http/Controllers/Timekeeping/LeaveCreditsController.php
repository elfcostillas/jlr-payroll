<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\LeaveCreditsMapper;
use App\Excel\CreditBalance;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveCreditsController extends Controller
{
    //
    private $mapper;
    private $excel;

    public function __construct(LeaveCreditsMapper $mapper, CreditBalance $excel)
    {
        $this->mapper = $mapper;
        $this->excel = $excel;
    }

    public function index()
    {
        return view('app.timekeeping.leave-credits.index');
    }

    public function yearList()
    {
        $result =  $this->mapper->yearList();

        return response()->json($result);
    }

    public function empList(Request $request)
    {
        $year = $request->year;
        $result =  $this->mapper->empList($year);

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $data = $request->models;

        foreach($data as $emp)
        {
            //dd($emp['line_id']);
            if($emp['biometric_id'] && $emp['fy_year']){
                if($emp['line_id']){
                    $this->mapper->updateValid($emp);
                } else {
                    $this->mapper->insertValid($emp);
                }
            }
        }
    }

    public function download(Request $request)
    {
        $year = $request->year;

        $start = $year.'-01-01';
        $end = $year.'-12-31';

        $data = $this->mapper->process($year,$start,$end);

        $this->excel->setValues($data,$year,$start,$end);

        return Excel::download($this->excel,'LeaveCreditBalance.xlsx');

        //return view('app.timekeeping.leave-credits.year-balance',['data'=>$data,'year'=>$year,'start'=>$start,'end'=>$end]);
    }   

    public function showLeaves(Request $request)
    {
        $year = $request->year;
        $biometric_id = $request->biometric_id;

        $start = $year.'-01-01';
        $end = $year.'-12-31';
        $employee = $this->mapper->getEmployeeInfo($biometric_id);
        $data = $this->mapper->showLeaves($biometric_id,$start,$end);
       
        $leave_credits = $this->mapper->getLeaveCredits($biometric_id,$year);
        
        $pdf = PDF::loadView('app.timekeeping.leave-credits.print',['data' => $data,'leave_credits'=>$leave_credits,'employee' => $employee,'year' => $year])->setPaper('A4','portrait');

        return $pdf->stream('JLR-Leaves-Print.pdf'); 
        // //$pdf->output();
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        
        // $canvas = $dom_pdf ->get_canvas();
        // $canvas->page_text(510, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        // //return $pdf->download('JLR-DTR-Print.pdf'); 
        // return $pdf->stream('JLR-DTR-Print.pdf'); 
    }

    
}
