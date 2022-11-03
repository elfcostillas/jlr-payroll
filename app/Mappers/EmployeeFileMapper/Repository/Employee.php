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
        'ndays' => null,
        'pay_type' => null,
        'late' => null,
        'late_eq' => null,
        'overtime' => null,
        'night_diff' => null,
        'sss_prem' => null,
        'phil_prem' => null,
        'hdmf_contri' => null,
        'overtime_amount' => null,
        'daily_allowance' => null,
        'semi_monthly_allowance' => null,
        'lh_ot' => null,
        'lh_ot_amount' => null,
        'lhot_rd' => null,
        'lhot_rd_amount' => null,
        'sh_ot' => null,
        'sh_ot_amount' => null,
        'shot_rd' => null,
        'shot_rd_amount' => null,
        'sun_ot' => null,
        'sun_ot_amount' => null,
        'under_time' => null,
        'under_time_amount' => null,
        'vl_wpay' => null,
        'vl_wpay_amount' => null,
        'vl_wopay' => null,
        'vl_wopay_amount' => null,
        'sl_wpay' => null,
        'sl_wpay_amount' => null,
        'sl_wopay' => null, 
        'sl_wopay_amount' => null,
        'bl_wpay' => null,
        'bl_wpay_amount' => null,
        'bl_wopay' => null,
        'bl_wopay_amount' => null,
        'absences' => null,
        'absences_amount' => null


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
        if($this->payreg['overtime']>0)
        {
            $this->payreg['overtime_amount'] = round(($this->rates['hourly_rate'] * 1.25) * $this->payreg['overtime'],2);
            //dd($this->payreg['overtime_amount'],$this->payreg['overtime'],$this->rates['hourly_rate']);
        }

        if($this->data['daily_allowance']>0){
            $this->payreg['daily_allowance'] = $this->data['daily_allowance'] * $this->payreg['ndays'];
        }

        if($this->data['monthly_allowance']>0){
            $this->payreg['semi_monthly_allowance'] = round($this->data['monthly_allowance']/2,2);
        }

        if($this->data['sh_ot']>0){
            $this->payreg['sh_ot_amount'] = round(($this->rates['hourly_rate'] * 1.3) * $this->payreg['sh_ot'],2);
        }

        if($this->data['vl_wpay']>0){
           // dd($this->data['vl_wpay'] ,$this->rates['hourly_rate']);
           $this->payreg['vl_wpay'] = $this->data['vl_wpay'];
            $this->payreg['vl_wpay_amount'] = round($this->data['vl_wpay'] * $this->rates['hourly_rate'],2);
        }

        // if($this->data['vl_wopay']>0){

        // }

        if($this->data['sl_wpay']>0){
            $this->payreg['sl_wpay'] = $this->data['sl_wpay'];
            $this->payreg['sl_wpay_amount'] = round($this->data['sl_wpay'] * $this->rates['hourly_rate'],2);
            
        }

        $this->payreg['absences'] =  $this->data['late_eq'] + $this->data['under_time'] + $this->data['vl_wopay'] + $this->data['sl_wopay'] + $this->data['bl_wopay'];
        $this->payreg['absences_amount'] = $this->payreg['absences'] * $this->rates['hourly_rate'];


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