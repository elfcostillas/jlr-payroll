<?php

namespace App\Libraries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;

class LeaveCreditsMaker {

	public function __invoke($date){
        $this->local_function($date);
	}

    public function local_function($date)
    {
       
        // dd(now()->format('Y-m-d'));

        /* Check if start of Year */
        $curdate = $date->format('Y-m-d');
        // Storage::disk('local')->put('file.txt', $date);

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
                        'sick_leave' => 5,
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

                $vl = $multiplier * 0.83;
                $sl = $multiplier * 0.42;
               
                array_push($tmp_credits,
                    array(
                        'fy_year' => $c_year,
                        'biometric_id' => $emp->biometric_id,
                        'vacation_leave' =>  $this->customRound($vl),
                        'sick_leave' => $this->customRound($sl),
                    )
                );
            }

        }

        DB::table('leave_credits')->insertOrIgnore($tmp_credits);       
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
}