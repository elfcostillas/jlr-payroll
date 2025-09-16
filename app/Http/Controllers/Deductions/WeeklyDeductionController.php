<?php

namespace App\Http\Controllers\Deductions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mappers\Compensation\OtherIncomeWeeklyAppHeaderMapper;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use Carbon\Carbon;

class WeeklyDeductionController extends Controller
{
    //
    private $mapper;
    private $period;

    public function __construct(OtherIncomeWeeklyAppHeaderMapper $mapper,PayrollPeriodWeeklyMapper $period)
    {
        $this->mapper = $mapper;
        $this->period = $period;
    }

    public function index()
    {
        return view('app.deductions.weekly.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function employeeList(Request $request)
    {
        $result = $this->mapper->empList($request->period_id);

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $data = $request->models;
        
        foreach($data as $line)
        {
            $result = $this->mapper->updateOrCreate([
                                                        'period_id'=>$line['period_id'],
                                                        'biometric_id'=>$line['biometric_id']
                                                    ],
                                                    [
                                                        "earnings" => $line['earnings'],
                                                        "deductions" => $line['deductions'],
                                                        "retro_pay" => $line['retro_pay'],
                                                        "canteen" => $line['canteen'],
                                                        "office_account" => $line['office_account'],
                                                        "remarks2" => $line['remarks2']
                                                    ]);
        }

        return response()->json(true);
    }

    public function print(Request $request)
    {
        $period = $request->period_id;

        $p = $this->period->find($period);

        $from = Carbon::createFromFormat('Y-m-d',$p->date_from);
        $to = Carbon::createFromFormat('Y-m-d',$p->date_to);

        $label = $from->format('m/d/Y').' - '.$to->format('m/d/Y');

        $result = $this->mapper->empList($request->period_id);

        $pdf = PDF::loadView('app.deductions.weekly.print',['data' => $result,'label' => $label])->setPaper('A4','portrait');
        
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
       
        $canvas = $dom_pdf->get_canvas();

        $canvas->page_text(510, 812, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $canvas->page_text(30, 812,"Date/Time : ".now()->format('m/d/y H:i:s'), null, 10, array(0, 0, 0));
        // return  $dom_pdf;
        return $pdf->stream('JLR-Canteen-Print.pdf'); 
    }
}


/*
"biometric_id" => "205"
"employee_name" => "Abalorio, Raul"
"period_id" => "26"
"earnings" => "0.00"
"deductions" => "8121.00"
"retro_pay" => "0.00"
"canteen" => "0.00"
"remarks" => null
"canteen_bpn" => "0.00"
"canteen_bps" => "0.00"
"canteen_agg" => "0.00"
*/