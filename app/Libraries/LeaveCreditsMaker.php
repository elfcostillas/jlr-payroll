<?php

namespace App\Libraries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveCreditsMaker {

	

	public function __invoke($date){
        $this->local_function($date);
	}

    public function local_function($date)
    {
       
        // dd(now()->format('Y-m-d'));

        /* Check if start of Year */
        $curdate = $date->format('Y-m-d');

        $now = $date->format('m-d');
        $c_year = $date->format('Y');

        $tmp_credits = [];

        if($now == '01-01'){
            $employees = DB::table('employees')->whereIn('pay_type',[1,2])
            ->whereRaw("DATEDIFF('".$curdate."',date_hired) >= 365")
            ->whereNotNull('date_hired')
            ->where('exit_status',1);

            $employees->select(DB::raw("biometric_id,date_hired,DATEDIFF('".$curdate."',date_hired) age_days "));

            foreach($employees->get() as $emp){
                array_push($tmp_credits,
                    array(
                        'fy_year' => $c_year,
                        'biometric_id' => $emp->biometric_id,
                        'vacation_leave' => 10,
                        'sick_leave' => 2,
                    )
                );
            }
            echo '01-01'.'<br>';

        }else{
            $employees = DB::table('employees')->whereIn('pay_type',[1,2])
            ->whereRaw(" DATE_ADD(date_hired,INTERVAL 365 DAY) ='".$curdate."'")
            ->whereNotNull('date_hired')
            ->where('exit_status',1);

            $employees->select(DB::raw("biometric_id,date_hired,DATEDIFF('".$curdate."',date_hired) age_days,MONTH(date_hired) AS m "))->get();
            
            foreach($employees->get() as $emp){
                $multiplier = 13 - $emp->m;
               
                array_push($tmp_credits,
                    array(
                        'fy_year' => $c_year,
                        'biometric_id' => $emp->biometric_id,
                        'vacation_leave' => $multiplier * 0.83,
                        'sick_leave' =>  $multiplier * 0.16,
                    )
                );
            }

        }

        DB::table('leave_credits')->insertOrIgnore($tmp_credits);       
    }
}