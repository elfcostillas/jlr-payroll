<?php

namespace App\Mappers\EmployeeFileMapper\Repository;

class Employee
{
    protected $data;
    protected $repo;

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
        'night_diff' => null
    ]; 

    public function __construct($data,$repo)
    {   
        $this->data = $data;
        $this->repo = $repo;
    }

    public function compute()
    {   
        //$this->payreg = $this->data;
        $this->payreg['basic_pay'] = $this->repo->getBasicPay($this->data);
        //$this->payreg['biometric_id'] = $this->data->biometric_id;
        //$this->showData();

        foreach($this->payreg as $key => $value){
            
            //dd(array_key_exists($key,$this->data),$this->data[$key]);
            if(array_key_exists($key,$this->data->toArray())){
                $this->payreg[$key] = $this->data[$key];
            }
        }
    }

    public function showData()
    {
       //  echo "<pre>".print_r($this->payreg)."</pre>";
    }

    public function toColumnArray()
    {
        return $this->payreg;
    }
}

?>