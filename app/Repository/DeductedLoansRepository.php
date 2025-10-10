<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class DeductedLoansRepository
{
    //

    public function getDeductedLoans($periods,$array)
    {

        $data = DB::table('employees');

        switch($array['emp_type']) {
          case 'sg' :
                $data->join('posted_loans_sg','posted_loans_sg.biometric_id','=','employees.biometric_id')
                ->join('loan_types','posted_loans_sg.deduction_type','=','loan_types.id');
                $data->where('employees.emp_level','>',5)
                ->whereIn('posted_loans_sg.period_id',$periods);
                break;

            case 'confi' :
                $data->join('posted_loans','posted_loans.biometric_id','=','employees.biometric_id')
                ->join('loan_types','posted_loans.deduction_type','=','loan_types.id');
                $data->where('employees.emp_level','<',5)
                ->whereIn('posted_loans.period_id',$periods);
                break;

            case 'semi' :
                $data->join('posted_loans','posted_loans.biometric_id','=','employees.biometric_id')
                ->join('loan_types','posted_loans.deduction_type','=','loan_types.id');
                 $data->where('employees.emp_level','=',5)
                 ->whereIn('posted_loans.period_id',$periods);

                break;
        }
        $data->where('loan_types.id',$array['loan_type']);
        $data->select(DB::raw("lastname,firstname,tin_no,phic_no,hdmf_no,sss_no,loan_types.description,SUM(amount) AS amount"));

        $data->groupByRaw('lastname,firstname,tin_no,phic_no,hdmf_no,sss_no,loan_types.description')
        ->orderBy('lastname','asc')
        ->orderBy('firstname','asc');

        return $data->get();
       
    }
}


/*


SELECT lastname,firstname,tin_no,phic_no,hdmf_no,sss_no,loan_types.description,SUM(amount) AS amount FROM employees
INNER JOIN posted_loans ON posted_loans.biometric_id = employees.biometric_id
INNER JOIN loan_types ON posted_loans.deduction_type = loan_types.id
WHERE employees.emp_level < 5
AND posted_loans.period_id IN (67,66)
GROUP BY lastname,firstname,tin_no,phic_no,hdmf_no,sss_no,loan_types.description



 */