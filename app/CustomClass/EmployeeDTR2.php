<?php

namespace App\CustomClass;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeDTR2
{
    //
    protected $biometric_id;
    protected $period_id;
    protected $details;
    protected $row = [

    ];

    public function __construct($biometric_id,$period_id)
    {
        
        $this->biometric_id = $biometric_id->biometric_id;
        $this->period_id = $period_id;

        $this->row['biometric_id'] = $this->biometric_id;
        $this->row['period_id'] = $this->period_id;
          
        $this->getEmployeeInfo();

        $this->compute_leaves();

        $this->compute_ot();

        $holidays = $this->getLegalHolidays();
        $sp_holidays = $this->getSpecialHolidays();

        $this->compute_ndays($holidays,$sp_holidays);
        //$this->computeHolidays($holidays,$sp_holidays);

        $this->computeHolidays($holidays,$sp_holidays);
    
        $this->save();
    }

    public function getEmployeeInfo()
    {
        $e = DB::table('employees')->where('biometric_id',(int) $this->biometric_id)->first();
        $this->details = $e;
    }

    public function compute_ot()
    {
        //over_time

        $result = DB::table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereRaw("edtr_detailed.dtr_date between date_from and date_to");
        })
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->select(DB::raw("
            sum(ifnull(late,0)) as late,
            round(sum(ifnull(late,0))/60,2) as late_eq,
            sum(over_time) as over_time,
            sum(night_diff) as night_diff,
            sum(night_diff_ot) as night_diff_ot,
            sum(restday_ot) as restday_ot,
            sum(restday_hrs) as restday_hrs,
            sum(restday_nd) as restday_nd,
            sum(restday_ndot) as restday_ndot,
            sum(reghol_ot) as reghol_ot,
            sum(reghol_rd) as reghol_rd,
            sum(reghol_rdnd) as reghol_rdnd,
            sum(reghol_rdot) as reghol_rdot,
            sum(reghol_nd) as reghol_nd,
            sum(reghol_ndot) as reghol_ndot,
            sum(sphol_pay) as sphol_pay,
            sum(sphol_hrs) as sphol_hrs,
            sum(sphol_ot) as sphol_ot,
            sum(sphol_rd) as sphol_rd,
            sum(sphol_rdnd) as sphol_rdnd,
            sum(sphol_rdot) as sphol_rdot,
            sum(sphol_nd) as sphol_nd,
            sum(sphol_ndot) as sphol_ndot,
            sum(dblhol_pay) as dblhol_pay,
            sum(dblhol_ot) as dblhol_ot,
            sum(dblhol_rd) as dblhol_rd,
            sum(sphol_rdndot) as sphol_rdndot,
            sum(reghol_rdndot) as reghol_rdndot,
            sum(awol) as awol
            "))
        ->first();

        // if($this->biometric_id==27){
        //     dd($result);
        // }

        // $this->row['over_time'] = $result->over_time;
        foreach($result  as $key => $value)
        {
            // dd($key,$value);
            if($key == 'awol'){
                $this->row[$key] = round($value/8,2);
            }else{
                $this->row[$key] = $value;
            }
            
        }


        /*
        sum(night_diff) as night_diff,
        sum(night_diff_ot) as night_diff_ot,
        sum(restday_ot) as restday_ot,
        sum(restday_hrs) as restday_hrs,
        sum(restday_nd) as restday_nd,
        sum(restday_ndot) as restday_ndot,
        sum(reghol_ot) as reghol_ot,
        sum(reghol_rd) as reghol_rd,
        sum(reghol_rdnd) as reghol_rdnd,
        sum(reghol_rdot) as reghol_rdot,
        sum(reghol_nd) as reghol_nd,
        sum(reghol_ndot) as reghol_ndot,
        sum(sphol_pay) as sphol_pay,
        sum(sphol_hrs) as sphol_hrs,
        sum(sphol_ot) as sphol_ot,
        sum(sphol_rd) as sphol_rd,
        sum(sphol_rdnd) as sphol_rdnd,
        sum(sphol_rdot) as sphol_rdot,
        sum(sphol_nd) as sphol_nd,
        sum(sphol_ndot) as sphol_ndot,
        sum(dblhol_pay) as dblhol_pay,
        sum(dblhol_ot) as dblhol_ot,
        sum(dblhol_hrsd) as dblhol_hrsd,
        sum(sphol_rdndot) as sphol_rdndot,
        sum(reghol_rdndot) as reghol_rdndot
        */
    }

    public function compute_ndays($holidays,$sp_holidays)
    {   
        //dd($holidays->count(),$sp_holidays);
        $hol_count = ($holidays) ? count($holidays) : 0;
        $sp_count = ($sp_holidays) ? count($sp_holidays) : 0;
        // 1 = Monthly | 2 = Daily
    
        $result = DB::table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereRaw("edtr_detailed.dtr_date between date_from and date_to");
        })
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->whereNotIn('dtr_date',$holidays)
        ->whereNotIn('dtr_date',$sp_holidays);

        // dd($result->toSql(),$result->getBindings());

        

        if($this->details->pay_type == 1){
            // $ndays = 13 - $this->row['vl_wp'] - $this->row['vl_wop'] - $this->row['sl_wp'] - $this->row['sl_wop'];
            $ndays = 13 - $this->row['vl_wp'] - $this->row['vl_wop'] - $this->row['sl_wp'] - $this->row['sl_wop'] - $hol_count - $sp_count;

            // dd($this->row['vl_wp'] , $this->row['vl_wop'] , $this->row['sl_wp'] , $this->row['sl_wop']);
        }else{
            $ndays_result = $result->select(DB::raw('sum(ndays) as ndays'))->first();
            $ndays = $ndays_result->ndays;
        }

        $awol = DB::table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereRaw("edtr_detailed.dtr_date between date_from and date_to");
        })
        ->select(DB::raw("ROUND(SUM(IFNULL(awol,0))/8,2) as awol_count"))
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->first();

        // set ndays
        $this->row['ndays'] = $ndays - $awol->awol_count;

    }

    public function compute_leaves()
    {
        // vl_wp | vl_wop | sl_wp | sl_wop

        $vl_qry = "select ifnull(sum(ifnull(with_pay,0)),0) as wp,ifnull(sum(ifnull(without_pay,0)),0) as wop from filed_leaves_vw 
                    inner join payroll_period on leave_date between payroll_period.date_from and payroll_period.date_to 
                    where payroll_period.id = $this->period_id and filed_leaves_vw.biometric_id = $this->biometric_id and leave_type = 'VL';";
        
        $vl = DB::select($vl_qry)[0];

        $sl_qry = "select ifnull(sum(ifnull(with_pay,0)),0) as wp,ifnull(sum(ifnull(without_pay,0)),0) as wop from filed_leaves_vw 
                    inner join payroll_period on leave_date between payroll_period.date_from and payroll_period.date_to 
                    where payroll_period.id = $this->period_id and filed_leaves_vw.biometric_id = $this->biometric_id and leave_type = 'SL';";

        $sl = DB::select($sl_qry)[0];

        $this->row['vl_wp'] = round($vl->wp/8,2);
        $this->row['vl_wop'] = round($vl->wop/8,2);
        $this->row['sl_wp'] = round($sl->wp/8,2);
        $this->row['sl_wop'] = round($sl->wop/8,2);
       
    }

     /*
        1 - Legal |  2 - Special |  3 - Double Legal |  4 - Company
    */

    public function getLegalHolidays()
    {
        $query = "select holiday_date from holidays inner join holiday_location on holidays.id = holiday_location.holiday_id
                inner join payroll_period on holidays.holiday_date between payroll_period.date_from and payroll_period.date_to
                left join employees on employees.location_id = holiday_location.location_id
                where payroll_period.id = {$this->period_id} and employees.biometric_id = {$this->biometric_id} and holidays.holiday_type = 1";
        
        
        $result = DB::select(DB::raw($query));

        $arr = [];

        foreach($result as $value)
        {
            array_push($arr,$value->holiday_date);
        }

        return $arr;
    }

    public function getSpecialHolidays()
    {
        $query = "select holiday_date from holidays inner join holiday_location on holidays.id = holiday_location.holiday_id
                inner join payroll_period on holidays.holiday_date between payroll_period.date_from and payroll_period.date_to
                left join employees on employees.location_id = holiday_location.location_id
                where payroll_period.id = {$this->period_id} and employees.biometric_id = {$this->biometric_id} and holidays.holiday_type = 2";
    
        // return DB::select(DB::raw($query));
        $result = DB::select(DB::raw($query));

        $arr = [];

        foreach($result as $value)
        {
            array_push($arr,$value->holiday_date);
        }

        return $arr;
    }

    function checkLastWorkingDay($holiday_date)
    {
        $flag = true;
        $ctr = 1;

        $isEntitled = false;

        $holiday = Carbon::createFromFormat("Y-m-d",$holiday_date);
       
        do {
            $holiday->subDay();
                

                // echo '['.$holiday->format('m/d/Y').']';
                //  if(($holiday->format('D')!='Sun') || (!in_array($holiday->format('D'), ['Sun','Sat'])) ){
                if(($holiday->format('D')!='Sun')){ 
                   
                    if($holiday->format('D')=='Sat'){
                        if($this->details->sched_sat && $this->details->alternate_sat=='N'){
                            $date = DB::table('holidays')
                            ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                            ->select()
                            ->where('holidays.holiday_date','=',$holiday->format('Y-m-d'))
                            ->where('holiday_location.location_id','=',$this->details->location_id);
    
                            $worked = DB::table('edtr_detailed')->select('ndays')
                                ->where('biometric_id','=',$this->biometric_id)
                                ->where('dtr_date','=',$holiday->format('Y-m-d'))
                                ->first();

                            if($date->count()<1){ // means it is not a holiday
                                $flag = false;
                                if($worked->ndays>0){
                                    $isEntitled = true;
                                }
                            } else {
                                if($worked->ndays>0){
                                    $isEntitled = true;
                                }
                            }
                            
                        }

                    }else{
                        $date = DB::table('holidays')
                        ->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
                        ->select()
                        ->where('holidays.holiday_date','=',$holiday->format('Y-m-d'))
                        ->where('holiday_location.location_id','=',$this->details->location_id);

                        $worked = DB::table('edtr_detailed')->select('ndays')
                            ->where('biometric_id','=',$this->biometric_id)
                            ->where('dtr_date','=',$holiday->format('Y-m-d'))
                            ->first();
                            
                        if($worked){
                            if($date->count()<1){ // means it is not a holiday
                                $flag = false;
                                if($worked->ndays>0){
                                    $isEntitled = true;
                                }
                            } else {
                                if($worked->ndays>0){
                                    $isEntitled = true;
                                }
                            }
                        }    
                        
                       
                       
                    }
            
                }else{
                    /* its sunday */
                   
                }
            
            if($flag){
                $ctr++;
                if($ctr>=15){
                    $flag = false;
                }

            }
           
        }while($flag);

        
      
        return $isEntitled;
    }

    public function grantHoliday($logs)
    {
        foreach($logs as $log)
        {
            $entitled = false;

            if($log->ndays > 0)
            {
                $entitled = true;
            }else{
                $entitled = $this->checkLastWorkingDay($log->dtr_date);
            }

            // if($entitled){ echo $log->dtr_date.'=grant'; }else{ echo $log->dtr_date.'=dont grant'; }
            // echo "<br>";

            if($entitled){
            
                switch($log->holiday_type) {
                    case 1 :
                            $log->reghol_pay = 1;
                        break;
                    
                    case 2 :
                        if($this->details->pay_type == 1){
                            $log->sphol_pay = 1;
                        }else{
                            
                            if($log->ndays == 1){
                                $log->sphol_pay = 1;
                            }else{
                                $log->sphol_pay = 0;
                            }
                        }
                        break;
                }

                $data = (array) $log;

                unset($data['holiday_type']);

                DB::table('edtr_detailed')->where('id', $log->id)->update($data);
                    
            }
          
        }
       
    }

    public function computeHolidays($holidays,$sp_holidays)
    {   
    
        if($holidays)
        {
            $reg_hol_logs = DB::table('edtr_detailed')
                    ->join('payroll_period',function($join){
                        $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
                    })
                    ->join('holidays','holidays.holiday_date','=','edtr_detailed.dtr_date')
                    ->select(DB::raw("edtr_detailed.*,holidays.holiday_type"))
                    ->where('payroll_period.id','=',$this->period_id)
                    ->where('biometric_id','=',$this->biometric_id)
                    ->whereIn('dtr_date',$holidays)
                    ->orderBy('dtr_date')
                    ->get();

            $this->grantHoliday($reg_hol_logs);
        }

        if($sp_holidays)
        {
            $sp_hol_logs = DB::table('edtr_detailed')
            ->join('payroll_period',function($join){
                $join->whereRaw('dtr_date between payroll_period.date_from and payroll_period.date_to');
            })
            ->join('holidays','holidays.holiday_date','=','edtr_detailed.dtr_date')
            ->select(DB::raw("edtr_detailed.*,holidays.holiday_type"))
            ->where('payroll_period.id','=',$this->period_id)
            ->where('biometric_id','=',$this->biometric_id)
            ->whereIn('dtr_date',$sp_holidays)
            ->orderBy('dtr_date')
            ->get();

            $this->grantHoliday($sp_hol_logs);
        }

        /*  
            Set no of Legal and Special Holiday
        */

        $legal = DB::table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereRaw("edtr_detailed.dtr_date between date_from and date_to");
        })
        ->select(DB::raw("sum(ifnull(reghol_pay,0)) as reghol_pay"))
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->whereIn('dtr_date',$holidays)
        ->first();

        $this->row['reghol_pay'] = (int) $legal->reghol_pay;

        $sp = DB::table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereRaw("edtr_detailed.dtr_date between date_from and date_to");
        })
        ->select(DB::raw("sum(ifnull(sphol_pay,0)) as sphol_pay"))
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->whereIn('dtr_date',$sp_holidays)
        ->first();

        $this->row['sphol_pay'] = (int) $sp->sphol_pay;
    }

    public function save()
    {
        
        // return DB::table('edtr_totals')->insert($this->row);
        return DB::table('edtr_totals')->updateOrInsert(['biometric_id' => $this->biometric_id , 'period_id' => $this->period_id],$this->row);
    }




}

/*
  `id` INT AUTO_INCREMENT NOT NULL,
  `biometric_id` INT NOT NULL,
  `period_id` INT NULL DEFAULT NULL ,
  `late` INT NULL DEFAULT 0 ,
  `late_eq` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `under_time` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `over_time` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `night_diff` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `night_diff_ot` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `schedule_id` INT NULL DEFAULT 0 ,
  `ndays` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `vl_wp` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `vl_wop` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `sl_wp` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `sl_wop` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `bl` DECIMAL(18,2) NULL DEFAULT 0.00 ,
  `ot_in` VARCHAR(5) NULL DEFAULT '00:00' ,
  `ot_out` VARCHAR(5) NULL DEFAULT '00:00' ,
  `restday_hrs` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `restday_ot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `restday_nd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `restday_ndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_pay` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_hrs` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_ot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_rd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_rdnd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_rdot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_nd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_ndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_pay` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_hrs` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_ot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_rd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_rdnd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_rdot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_nd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_ndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_pay` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_hrs` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_ot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_rd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_rdnd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_rdot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_nd` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_ndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `reghol_rdndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `sphol_rdndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  `dblhol_rdndot` DECIMAL(24,2) NULL DEFAULT 0.00 ,
  */