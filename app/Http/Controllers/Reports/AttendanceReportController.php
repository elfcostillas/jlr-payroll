<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceReportController extends Controller
{
    //
    private $dtr_mapper;

    public function __construct(DailyTimeRecordMapper $dtr_mapper) {
        $this->dtr_mapper  = $dtr_mapper;
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

    public function setAWOL($year)
    {
        $result = $this->dtr_mapper->awol_setter($year);
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

            

                $result = $result->get();

        

        // return view();
        return view('app.reports.attendance.sub',['data' => $result]);
    }


}
