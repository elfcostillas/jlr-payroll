<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\LeaveCreditsMapper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveCreditsSGController extends Controller
{
    //
    private $mapper;

    public function __construct(LeaveCreditsMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.leave-credits-sg.index');
    }

    public function yearList()
    {
        $result =  $this->mapper->yearList();

        return response()->json($result);
    }

    public function empList(Request $request)
    {
        $year = $request->year;
        $result =  $this->mapper->empListSG($year);

        return response()->json($result);
    }


    public function leaveCreditsMakerByYear()
    {

    }

    
    public function leaveCreditsMakerByDate()
    {

    }

    public function computeLeaveCredits(Request $request){

        // $this->compteToday($request->year);

        $this->computeOnYearStart($request->year);

        $date_from = Carbon::createFromDate(now()->format('Y'),1,2);
        $date_to = now();

        $range = CarbonPeriod::create($date_from,$date_to);

        foreach($range as $date)
        {
            $this->computeToday($date->format('Y-m-d'));
        }
    }

    public function computeToday($date)
    {
      
        $sample_date = Carbon::createFromFormat('Y-m-d',$date);
        $employees  = DB::table('employees')    
                ->where('exit_status',1)
                ->where('emp_level','>',5)
                ->whereNotNull('date_hired')
                ->select(DB::raw("biometric_id,DATEDIFF('".$date."',date_hired) as no_of_days,MONTH(date_hired) AS m"))
                ->having('no_of_days','=',365)
                ->get();

        // dd($employees->count());

        if($employees->count() > 0){
            foreach($employees as $employee)
            {
                $multiplier = 13 - $employee->m;

                // $sil = round(5/12,2) * $multiplier;
                $sil = round(round(5/12,2) * $multiplier,2);

                // dd($this->customRound($sil));
                DB::table('leave_credits')
                ->updateOrInsert(
                    [   
                        'fy_year' => $sample_date->format('Y'), 
                        'biometric_id' => $employee->biometric_id,
                    ],
                    ['sil' => $this->customRound($sil)]
                );
            }
        }
        
    }

    public function computeOnYearStart($year)
    {
        $employees  = DB::table('employees')    
                ->where('exit_status',1)
                ->where('emp_level','>',5)
                ->whereNotNull('date_hired')
                ->select(DB::raw("biometric_id,DATEDIFF('".$year.'-01-01'."',date_hired) as no_of_days"))
                ->having('no_of_days','>=',365)
                ->get();
    

        foreach($employees as $employee)
        {

            DB::table('leave_credits')
            ->updateOrInsert(
                [   
                    'fy_year' => $year, 
                    'biometric_id' => $employee->biometric_id,
                ],
                ['sil' => 5]
            );
        }
    }

    public function customRound($n)
    {
        $whole = $n;
        $num = (int) $n;

        $decimal = (float) $whole - $num;

        if($decimal < 0.4)
        {
          
            $new_decimal = 0;
        }else if($decimal >= 0.4 && $decimal <= 0.7)
        {
            $new_decimal = 0.5;
        }else{
            $new_decimal = 0.0;
            $num += 1;
        }

        return (float) $num + $new_decimal;

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

        // dd($leave_credits);
        
        $pdf = PDF::loadView('app.timekeeping.leave-credits-sg.print',['data' => $data,'leave_credits'=>$leave_credits,'employee' => $employee,'year' => $year])->setPaper('A4','portrait');
        // $pdf = PDF::loadView('app.timekeeping.leave-credits-sg.print')->setPaper('A4','portrait');

        return $pdf->stream('JLR-Leaves-Print.pdf'); 
    
    }

    
}
