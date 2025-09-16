<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    //
    private $dtr_mapper;
    private $emp_mapper;

    public function __construct(DailyTimeRecordMapper $dtr_mapper,EmployeeMapper $emp_mapper) {
        $this->dtr_mapper  = $dtr_mapper;
        $this->emp_mapper  = $emp_mapper;
    }

    public function index()
    {
        return view('app.reports.attendance.index');
    }

    public function generate($from,$to)
    {
        $result = $this->dtr_mapper->attendance_report($from,$to);

        $range = $from.'|'.$to;

        return view('app.reports.attendance.web',['data' => $result,'range' => $range]);
    }

    public function setAWOL($year,$month)
    {
        //echo "START => ".now()."<br>";
            $result = $this->dtr_mapper->awol_setter($year,$month);
        //echo "END => ".now()."<br>";

        // return view('app.reports.attendance.logs',['data' => $result]);

        return response()->json($result);
    }

    public function fillBlank()
    {
        /*
        
        SELECT employees.biometric_id,lastname,firstname,date_hired,no_of_days FROM employees 
        LEFT JOIN (SELECT biometric_id,COUNT(id) AS no_of_days FROM edtr WHERE dtr_date BETWEEN '2023-01-01' AND '2023-12-31' GROUP BY biometric_id) dtr
        ON dtr.biometric_id = employees.biometric_id
        WHERE date_hired < '2023-01-01' AND exit_status = 1
        AND no_of_days < 365;
        */ 

        $blank_dtr = [];
        
        $period = CarbonPeriod::create('2023-01-01','2023-12-31');

        // $employees = DB::table('edtr')
        //                 ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        //                 ->select(DB::raw("employees.biometric_id,COUNT(edtr.id) AS no_of_days,sched_mtwtf,sched_sat"))
        //                 ->whereBetween('dtr_date',['2023-01-01','2023-12-31'])->groupBy('employees.biometric_id')->havingRaw("no_of_days < 365")
        //                 ->whereRaw("date_hired < '2023-01-01'")
        //                 ->where('exit_status','=',1)
        //                 ->get();

        // dd($employees);

        $employees = DB::table("employees")
                        ->select('biometric_id','sched_mtwtf','sched_sat')
                        ->whereRaw("date_hired < '2023-01-01'")
                        ->where('exit_status','=',1)
                        ->where('pay_type','<>',3)
                        ->get();

      
        foreach($employees as $employee)
        {
            
            foreach ($period as $date) {
                switch ($date->format('D')){
                    case 'Mon': case 'Tue': case 'Wed': case 'Thu': case 'Fri':
                            $sched = $employee->sched_mtwtf;
                        break;
                        
                    case 'Sat' :
                            $sched = $employee->sched_sat;
                        break;
                    
                    default : 
                            $sched = 0;
                        break;
                }
                array_push($blank_dtr,['biometric_id' => $employee->biometric_id, 'dtr_date' => $date->format('Y-m-d'),'schedule_id' => $sched ]);
            }
        }

        $result = DB::table('edtr')->insertOrIgnore($blank_dtr);

        
    }

    public function sub($from,$to,$biometric_id,$type)
    {
        $result = DB::table('edtr')->whereBetween('dtr_date',[$from,$to])
                // ->select('dtr_date','time_in,time_out')
                ->select(
                    'dtr_date',
                    'time_in',
                    'time_out',
                    'late',
                    'under_time',
                    'vl_wp',
                    'vl_wop',
                    'sl_wp',
                    'sl_wop',
                    'other_leave',
                    'awol'
                )

                ->where('biometric_id','=',$biometric_id);

                if($type=="LATE"){
                    $result = $result->where('late','>',0);
                }   

                
                if($type=="UT"){
                    $result = $result->where('under_time','>',0);
                }   

                
                if($type=="AWOL"){
                    $result = $result->where('awol','=','Y');
                }   

            
                if($type=="VL"){
                    $result = $result->where(function($query){
                        $query->where('vl_wp','>',0)
                        ->orWhere('vl_wop','>',0);
                    });
                }   

                if($type=="SL"){
                    $result = $result->where(function($query){
                        $query->where('sl_wp','>',0)
                        ->orWhere('sl_wop','>',0);
                    });
                }   

            

                $result = $result->get();

        

        // return view();
        return view('app.reports.attendance.sub',['data' => $result]);
    }

    public function employees(Request $request)
    {
            $filter = [
                'take' => $request->input('take'),
                'skip' => $request->input('skip'),
                'pageSize' => $request->input('pageSize'),
                'filter' => $request->input('filter'),
                'sort' => $request->input('sort'),
            ];
    
            $result = $this->dtr_mapper->employeelist($filter);

            return response()->json($result);
    
    }

    public function downloadDTR(Request $request)
    {
        $biometric_id = $request->biometric_id;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $employee = $this->emp_mapper->getEmployeeDetails($biometric_id);
       
        $result = $this->dtr_mapper->dtr($biometric_id,$date_from,$date_to);

        return view('app.reports.attendance.emp_dtr',['data' => $result,'employee' => $employee]);

        // $this->excel->setValues($result);
        // return Excel::download($this->excel,'EmployeeLeaves.xlsx');

    }

    public function setTARDY($year)
    {
        $from = $year.'-01-01';
        $to = $year.'-12-31';

        $dtrs = DB::table('edtr')->join('employees','edtr.biometric_id', '=','employees.biometric_id')
                ->select(DB::raw("edtr.id,edtr.biometric_id,dtr_date,schedule_id,WEEKDAY(dtr_date) AS day_num,sched_mtwtf,sched_sat"))
                ->where('exit_status',1)->where('pay_type','<>',3)->where(function($query){
                    $query->where('edtr.schedule_id',0)->orWhereNull('edtr.schedule_id');
                })
                ->whereBetween('dtr_date',[$from,$to])
                ->whereRaw("WEEKDAY(dtr_date) <> 6 ")
                ->get();

        $ctr = 1;

        echo "START => ".now()."<br>";

        foreach($dtrs as $dtr){
          
            switch($dtr->day_num)
            {
                    case 0 : case '0' :
                    case 1 : case '1' :
                    case 2 : case '2' :
                    case 3 : case '3' :
                    case 4 : case '4' :
                        // dd($dtr->day_num);
                        $affected = DB::table('edtr')
                                        ->where('id', $dtr->id)
                                        ->update(['schedule_id' => $dtr->sched_mtwtf]);

                        $ctr++;
                    break;

                    case 5 :  case '5' :
                        // dd($dtr->day_num);
                        $affected = DB::table('edtr')
                                        ->where('id', $dtr->id)
                                        ->update(['schedule_id' => $dtr->sched_sat]);
                        $ctr++;
                    break;
            }
        }

        echo "Records Updated => ".$ctr."<br>";

        echo "END => ".now()."<br>";
    }


}


/*
SELECT edtr.id,edtr.biometric_id,dtr_date,schedule_id,WEEKDAY(dtr_date) AS day_num FROM edtr INNER JOIN employees ON edtr.biometric_id = employees.biometric_id 
WHERE exit_status = 1 AND pay_type <> 3 AND edtr.schedule_id = 0 AND dtr_date BETWEEN '2023-07-01' AND '2023-09-30'
AND WEEKDAY(dtr_date) != 6 ;
*/