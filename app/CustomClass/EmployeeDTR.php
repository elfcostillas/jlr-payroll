<?php

namespace App\CustomClass;

use Illuminate\Support\Facades\DB;

class EmployeeDTR
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

        $this->compute_ndays();
        

        $this->save();
    }

    public function getEmployeeInfo()
    {
        $e = DB::table('employees')->where('biometric_id',(int) $this->biometric_id)->first();
        $this->details = $e;
    }

    public function compute_ndays()
    {   
        // 1 = Monthly | 2 = Daily

        $holidays = $this->getLegalHolidays();
        $sp_holidays = $this->getSpecialHolidays();
    
        $result = DB::table('edtr')->join('payroll_period',function($join){
            $join->whereRaw("edtr.dtr_date between date_from and date_to");
        })
        ->where('biometric_id',$this->biometric_id)
        ->where('payroll_period.id',$this->period_id)
        ->whereNotIn('dtr_date',$holidays)
        ->whereNotIn('dtr_date',$sp_holidays);

        if($this->details->pay_type == 1){
            $ndays = 13 - $this->row['vl_wp'] - $this->row['vl_wop'] - $this->row['sl_wp'] - $this->row['sl_wop'];
        }else{
            $ndays_result = $result->select(DB::raw('sum(ndays) as ndays'))->first();
            $ndays = (int) $ndays_result->ndays;
        }
        
        // set ndays
        $this->row['ndays'] = $ndays;

    }

    public function compute_leaves()
    {
        // vl_wp | vl_wop | sl_wp | sl_wop

        $vl_qry = "select ifnull(sum(ifnull(with_pay,0)),0) as wp,ifnull(sum(ifnull(without_pay,0)),0) as wop from filed_leaves_vw 
                    inner join payroll_period on leave_date between payroll_period.date_from and payroll_period.date_to 
                    where payroll_period.id = $this->biometric_id and filed_leaves_vw.biometric_id = $this->biometric_id and leave_type = 'VL';";

        $vl = DB::select($vl_qry)[0];

        $sl_qry = "select ifnull(sum(ifnull(with_pay,0)),0) as wp,ifnull(sum(ifnull(without_pay,0)),0) as wop from filed_leaves_vw 
                    inner join payroll_period on leave_date between payroll_period.date_from and payroll_period.date_to 
                    where payroll_period.id = $this->biometric_id and filed_leaves_vw.biometric_id = $this->biometric_id and leave_type = 'SL';";

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
                where payroll_period.id = 50 and employees.biometric_id = 847 and holidays.holiday_type = 1";
    
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
                where payroll_period.id = 50 and employees.biometric_id = 847 and holidays.holiday_type = 2";
    
        // return DB::select(DB::raw($query));
        $result = DB::select(DB::raw($query));

        $arr = [];

        foreach($result as $value)
        {
            array_push($arr,$value->holiday_date);
        }

        return $arr;
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