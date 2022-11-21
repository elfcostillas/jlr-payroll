<?php

namespace App\Mappers\EmployeeFileMapper\Repository;
use Illuminate\Support\Facades\DB;

class Employee
{
    protected $data;
    protected $repo;
    protected $philrate;

    protected $payreg = [
        'period_id' => null,
        'biometric_id' => null,
        'basic_pay' =>null,
        'basic_salary' => null,
        'is_daily' => null,
        'ndays' => 0.00,
        'pay_type' => null,
        'late' => 0.00,
        'late_eq' => 0.00,
        'late_eq_amount' => 0.00,
        'sss_prem' => 0.00,
        'phil_prem' => 0.00,
        'hdmf_contri' => 0.00,
        'daily_allowance' => 0.00,
        'semi_monthly_allowance' => 0.00,
        'under_time' => 0.00,
        'under_time_amount' => 0.00,
        'vl_wpay' => 0.00,
        'vl_wpay_amount' => 0.00,
        'vl_wopay' => 0.00,
        'vl_wopay_amount' => 0.00,
        'sl_wpay' => 0.00,
        'sl_wpay_amount' => 0.00,
        'sl_wopay' => 0.00, 
        'sl_wopay_amount' => 0.00,
        'bl_wpay' => 0.00,
        'bl_wpay_amount' => 0.00,
        'bl_wopay' => 0.00,
        'bl_wopay_amount' => 0.00,
        'absences' => 0.00,
        'absences_amount' => 0.00,
        'reg_ot'	=> 0.00,
        'reg_ot_amount'	=> 0.00,
        'reg_nd'	=> 0.00,
        'reg_nd_amount'	=> 0.00,
        'reg_ndot'	=> 0.00,
        'reg_ndot_amount'	=> 0.00,
        'rd_hrs'	=> 0.00,
        'rd_hrs_amount'	=> 0.00,
        'rd_ot'	=> 0.00,
        'rd_ot_amount'	=> 0.00,
        'rd_nd'	=> 0.00,
        'rd_nd_amount'	=> 0.00,
        'rd_ndot'	=> 0.00,
        'rd_ndot_amount'	=> 0.00,
        'leghol_count'	=> 0.00,
        'leghol_count_amount'	=> 0.00,
        'leghol_hrs'	=> 0.00,
        'leghol_hrs_amount'	=> 0.00,
        'leghol_ot'	=> 0.00,
        'leghol_ot_amount'	=> 0.00,
        'leghol_nd'	=> 0.00,
        'leghol_nd_amount'	=> 0.00,
        'leghol_rd'	=> 0.00,
        'leghol_rd_amount'	=> 0.00,
        'leghol_rdot'	=> 0.00,
        'leghol_rdot_amount'	=> 0.00,
        'leghol_ndot'	=> 0.00,
        'leghol_ndot_amount'	=> 0.00,
        'leghol_rdnd'	=> 0.00,
        'leghol_rdnd_amount'	=> 0.00,
        'leghol_rdndot'	=> 0.00,
        'leghol_rdndot_amount'	=> 0.00,
        'sphol_count'	=> 0.00,
        'sphol_count_amount'	=> 0.00,
        'sphol_hrs'	=> 0.00,
        'sphol_hrs_amount'	=> 0.00,
        'sphol_ot'	=> 0.00,
        'sphol_ot_amount'	=> 0.00,
        'sphol_nd'	=> 0.00,
        'sphol_nd_amount'	=> 0.00,
        'sphol_rd'	=> 0.00,
        'sphol_rd_amount'	=> 0.00,
        'sphol_rdot'	=> 0.00,
        'sphol_rdot_amount'	=> 0.00,
        'sphol_ndot'	=> 0.00,
        'sphol_ndot_amount'	=> 0.00,
        'sphol_rdnd'	=> 0.00,
        'sphol_rdnd_amount'	=> 0.00,
        'sphol_rdndot'	=> 0.00,
        'sphol_rdndot_amount'	=> 0.00,
        'dblhol_count'	=> 0.00,
        'dblhol_count_amount'	=> 0.00,
        'dblhol_hrs'	=> 0.00,
        'dblhol_hrs_amount'	=> 0.00,
        'dblhol_ot'	=> 0.00,
        'dblhol_ot_amount'	=> 0.00,
        'dblhol_nd'	=> 0.00,
        'dblhol_nd_amount'	=> 0.00,
        'dblhol_rd'	=> 0.00,
        'dblhol_rd_amount'	=> 0.00,
        'dblhol_rdot'	=> 0.00,
        'dblhol_rdot_amount'	=> 0.00,
        'dblhol_ndot'	=> 0.00,
        'dblhol_ndot_amount'	=> 0.00,
        'dblhol_rdnd'	=> 0.00,
        'dblhol_rdnd_amount'	=> 0.00,
        'dblhol_rdndot'	=> 0.00,
        'dblhol_rdndot_amount'	=> 0.00,



    ]; 

    protected $rates = [
        'monthly_credit' => null,
        'daily_rate' => null,
        'hourly_rate' => null
    ];

    public function __construct($data,$repo)
    {   
        $this->data = $data;
        $this->repo = $repo;
    }

    public function compute($period)
    {   
       
        $this->payreg['basic_pay'] = $this->repo->getBasicPay($this->data);
        $this->rates['monthly_credit'] = $this->repo->getMonthlyCredit($this->data);
        $this->setPayRates();
        foreach($this->payreg as $key => $value){
            if(array_key_exists($key,$this->data->toArray())){
                $this->payreg[$key] = $this->data[$key];
            }
        }
        $this->computeContribution($period);

        /*** Overtime ***/
        // if($this->payreg['overtime']>0)
        // {
        //     $this->payreg['overtime_amount'] = round(($this->rates['hourly_rate'] * 1.25) * $this->payreg['overtime'],2);
        //     //dd($this->payreg['overtime_amount'],$this->payreg['overtime'],$this->rates['hourly_rate']);
        // }

        // if($this->data['daily_allowance']>0){
        //     $this->payreg['daily_allowance'] = $this->data['daily_allowance'] * $this->payreg['ndays'];
        // }

        // if($this->data['monthly_allowance']>0){
        //     $this->payreg['semi_monthly_allowance'] = round($this->data['monthly_allowance']/2,2);
        // }

        // if($this->data['sh_ot']>0){
        //     $this->payreg['sh_ot_amount'] = round(($this->rates['hourly_rate'] * 1.3) * $this->payreg['sh_ot'],2);
        // }

        // if($this->data['vl_wpay']>0){
        //    // dd($this->data['vl_wpay'] ,$this->rates['hourly_rate']);
        //    $this->payreg['vl_wpay'] = $this->data['vl_wpay'];
        //     $this->payreg['vl_wpay_amount'] = round($this->data['vl_wpay'] * $this->rates['hourly_rate'],2);
        // }

        // // if($this->data['vl_wopay']>0){

        // // }

        // if($this->data['sl_wpay']>0){
        //     $this->payreg['sl_wpay'] = $this->data['sl_wpay'];
        //     $this->payreg['sl_wpay_amount'] = round($this->data['sl_wpay'] * $this->rates['hourly_rate'],2);
            
        // }

        // $this->payreg['absences'] =  $this->data['late_eq'] + $this->data['under_time'] + $this->data['vl_wopay'] + $this->data['sl_wopay'] + $this->data['bl_wopay'];
        // $this->payreg['absences_amount'] = $this->payreg['absences'] * $this->rates['hourly_rate'];


        // if($this->data['sl_wopay']>0){

        // }
       
        /*******/

    }

    public function computeContribution($period){
       
        if($period->period_type==1){
            $this->payreg['hdmf_contri'] = $this->data['hdmf_contri'];
            $this->payreg['sss_prem'] = 0.00;
            $this->payreg['phil_prem'] = 0.00;
        }else{
            $this->payreg['hdmf_contri'] = 0.00;
            $this->payreg['sss_prem'] = ($this->data['deduct_sss']=='Y') ?  $this->computeSSSPrem() : 0.00;
            $this->payreg['phil_prem'] = ($this->data['deduct_phic']=='Y') ?  round(($this->rates['monthly_credit'] * ($this->philrate/100))/2,2) : 0.00;
        
        }
    }

    public function setPayRates(){
        if($this->data['is_daily']=='Y'){
            $this->rates["daily_rate"] = $this->data['basic_salary'];
            $this->rates["hourly_rate"] = (float) round($this->rates['daily_rate']/8,4);
        }else{
            $this->rates["daily_rate"] = (float) round(($this->data['basic_salary']*12)/314,4);
            $this->rates["hourly_rate"] = (float) round($this->rates['daily_rate']/8,4);
        }
    }

    public function setPhilRate($rate){
        $this->philrate = $rate;
    }

    public function computeSSSPrem()
    {
        $prem = DB::table('hris_sss_table_2021')->select('ee_share')->whereRaw($this->rates['monthly_credit']." between range1 and range2")->first();
        return (float)$prem->ee_share;
    }

    public function toColumnArray()
    {
        return $this->payreg;
    }

    
}

/*
1-15 
    -HDMF

16-30
    -SSS Prem
    -PHIL Prem
*/

?>