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
        'under_time' => null,
        'overtime' => null,
        'night_diff' => null,
        'sss_prem' => null,
        'phil_prem' => null,
        'hdmf_contri' => null,
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
        $this->computeContribution($period);

        foreach($this->payreg as $key => $value){
            
            if(array_key_exists($key,$this->data->toArray())){
                $this->payreg[$key] = $this->data[$key];
            }
        }

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
            $this->rates["hourly_rate"] = (float) round($this->data['daily_rate']/8,4);
        }else{
            $this->rates["daily_rate"] = (float) round(($this->data['basic_salary']/2)/13,4);
            $this->rates["hourly_rate"] = (float) round($this->data['daily_rate']/8,4);
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