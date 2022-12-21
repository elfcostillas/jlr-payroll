<?php

namespace App\Mappers\EmployeeFileMapper\Repository;

class SemiMonthly
{
    public function compute()
    {
        
    }

    function getBasicPay($data)
    {
        //dd($data);
        return (float) round($data['basic_salary']/2,2) - $data['late_eq_amount'] - $data['under_time_amount'] - $data['vl_wpay_amount'] - $data['sl_wpay_amount']
        - $data['absences_amount'] - $data['leghol_count_amount'] - $data['sphol_count_amount'] - $data['bl_wpay_amount'];
    }

    function getGrossPay($payreg){
        return $this->getBasicPay($payreg) + $payreg['vl_wpay_amount'] + $payreg['sl_wpay_amount'] + $payreg['bl_wpay_amount']
                        + $payreg['reg_ot_amount'] +  $payreg['reg_nd_amount'] + $payreg['reg_ndot_amount'] 
                        + $payreg['rd_hrs_amount'] + $payreg['rd_ot_amount'] + $payreg['rd_nd_amount'] + $payreg['rd_ndot_amount'] 
                        + $payreg['leghol_count_amount'] + $payreg['leghol_hrs_amount'] + $payreg['leghol_ot_amount'] + $payreg['leghol_nd_amount']
                        + $payreg['leghol_rd_amount'] + $payreg['leghol_rdot_amount'] + $payreg['leghol_ndot_amount'] + $payreg['leghol_rdndot_amount']
                        + $payreg['sphol_count_amount'] + $payreg['sphol_hrs_amount'] + $payreg['sphol_ot_amount'] + $payreg['sphol_nd_amount']
                        + $payreg['sphol_rd_amount'] + $payreg['sphol_rdot_amount'] + $payreg['sphol_ndot_amount'] + $payreg['sphol_rdndot_amount']
                        + $payreg['dblhol_count_amount'] + $payreg['dblhol_hrs_amount'] + $payreg['dblhol_ot_amount'] + $payreg['dblhol_nd_amount']
                        + $payreg['dblhol_rd_amount'] + $payreg['dblhol_rdot_amount'] + $payreg['dblhol_ndot_amount'] + $payreg['dblhol_rdndot_amount'] + $payreg['semi_monthly_allowance'] + $payreg['daily_allowance'];
    }

    function getMonthlyCredit($data){
        //dd($data);
        if($data['is_daily']=='Y'){
            $monthly_credit = $data['basic_salary'] * 26;
        }else{
            $monthly_credit = $data['basic_salary'];
        }

        return  (float) $monthly_credit;
    }
}

?>