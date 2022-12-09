<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayslipMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\Location';
    protected $rules = [
    	
    ];

    public function getPostedPeriods()
    {
        //SELECT period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range FROM posting_info 
        //INNER JOIN payroll_period ON payroll_period.id = posting_info.period_id ORDER BY period_id DESC ;
        $result = $this->model->select(DB::raw("period_id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range"))
                                ->from('posting_info')->join('payroll_period','payroll_period.id','=','posting_info.period_id')
                                ->where('trans_type','non-confi')
                                ->orderBy('period_id','DESC');

        return $result->get();
    }    

    public function getPeriodLabel($period)
    {
        //SELECT CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_range FROM payroll_period WHERE id = ''
        $result = $this->model->select(DB::raw("CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range"))
        ->from('payroll_period')
        ->where('id',$period);
        
        return $result->first();
    }

    public function getEmployees($period,$division,$department)
    {
        $user = Auth::user();

        $result = $this->model->select(DB::raw("employees.biometric_id,concat(lastname,', ',firstname) as emp_name,suffixname"))
                ->from('payrollregister_posted_s')
                ->join('employees','employees.biometric_id','=','payrollregister_posted_s.biometric_id')
                //->where('period_id',$period)
                ->distinct();
                
        if($period!=0){
            $result->where('period_id',$period);
        }

        if($division!=0){
            $result->where('division_id',$division);
        }

        if($department!=0){
            $result->where('dept_id',$department);
        }

        if($user->super_user=='N')
        {
            $result = $result->where('emp_level','>=',5);
        }
        else
        {
            $result = $result->where('emp_level','<',5);
        }

        $result->orderBy('lastname','ASC')->orderBy('firstname','ASC');

        // dd($result->toSql(),$result->getBindings());
        return $result->get();
                
    }

    public function getData($period_id,$division,$department,$biometric_id)
    {
        //dd($period_id,$division,$department,$biometric_id);

        $result = $this->model->select(DB::raw("payrollregister_posted_s.*,dept_id,division_id,concat(lastname,', ',firstname) as employee_name,suffixname,dept_name"))
                    ->from('payrollregister_posted_s')
                    ->join('employees','employees.biometric_id','=','payrollregister_posted_s.biometric_id')
                    ->leftJoin('departments','departments.id','=','dept_id');

        if($period_id != 0 && $period_id != "" && $period_id != null){
            $result->where('period_id',$period_id);
        }

        if($division != 0 && $division != "" && $division != null){
            $result->where('division_id',$division);
        }

        if($department != 0 && $department != "" && $department != null){
            $result->where('dept_id',$department);
        }

        if($biometric_id != 0 && $biometric_id != "" && $biometric_id != null){
            $result->where('employees.biometric_id',$biometric_id);
        }

        $data = $result->get();

       
        foreach($data as $epay)
        {
            if(is_object($epay)){
                $epay->basic = $this->basic($epay);
                $epay->gov_loan = $this->paySlipGovLoan($period_id,$epay->biometric_id);
                $epay->reg_earnings = $this->regEarnings($epay);
                $epay->restday = $this->restDay($epay);
                $epay->legalHol = $this->legalHol($epay);
                $epay->specialHol = $this->specialHol($epay);
                $epay->dblLegHol = $this->dblLegHol($epay);
                $epay->allowances = $this->allowances($epay);
                $epay->otherEearnings = $this->otherEearnings($period_id,$epay->biometric_id);
                $epay->slvl = $this->slvl($epay);
                $epay->fixedDeduction = $this->fixedDeduction($period_id,$epay->biometric_id);
                $epay->installments = $this->installments($period_id,$epay->biometric_id);
            }
            
            
        }

        return $data;
    }

    public function basic($e)
    {
        $earnings=[];
        
        array_push($earnings, (object) [
            'name' => 'Basic Pay (Days)',
            'days'=> $e->ndays,
            'amount' => $e->basic_pay
        ]);

        array_push($earnings, (object) [
            'name' => 'Late (Hrs)',
            'days'=> $e->late_eq,
            'amount' => $e->late_eq_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Undertime (Hrs)',
            'days'=> $e->under_time,
            'amount' => $e->under_time_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Absent (Hrs)',
            'days'=> $e->absences,
            'amount' => $e->absences_amount
        ]);
        
        return collect($earnings);
    }

    public function legalHol($e)
    {
        $earnings=[];

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Pay',
            'days'=> $e->leghol_count,
            'amount' => $e->leghol_count_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday (Hrs)',
            'days'=> $e->leghol_hrs,
            'amount' => $e->leghol_hrs_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday O.T. (Hrs)',
            'days'=> $e->leghol_ot,
            'amount' => $e->leghol_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Night Diff. (Hrs)',
            'days'=> $e->leghol_nd,
            'amount' => $e->leghol_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Rest Day (Hrs)',
            'days'=> $e->leghol_rd,
            'amount' => $e->leghol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Rest Day O.T. (Hrs)',
            'days'=> $e->leghol_rdot,
            'amount' => $e->leghol_rdot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Night Diff O.T. (Hrs)',
            'days'=> $e->leghol_ndot,
            'amount' => $e->leghol_ndot_amount
        ]);


        array_push($earnings, (object) [
            'name' => 'Leg. Holiday R.D. N.D. (Hrs)',
            'days'=> $e->leghol_rdnd,
            'amount' => $e->leghol_rdnd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday R.D. N.D. O.T. (Hrs)',
            'days'=> $e->leghol_rdndot,
            'amount' => $e->leghol_rdndot_amount
        ]);

        $total = $e->leghol_count_amount + $e->leghol_hrs_amount + $e->leghol_ot_amount + $e->leghol_nd_amount + $e->leghol_rd_amount + $e->leghol_rdot_amount + $e->leghol_ndot_amount +$e->leghol_rdnd_amount +$e->leghol_rdndot_amount;

        return array('list'=>collect($earnings),'total'=> $total );
    }

    public function specialHol($e)
    {
        $earnings=[];

        array_push($earnings, (object) [
            'name' => 'Special Holiday Pay',
            'days'=> $e->sphol_count,
            'amount' => $e->sphol_count_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday (Hrs)',
            'days'=> $e->sphol_hrs,
            'amount' => $e->sphol_hrs_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday O.T. (Hrs)',
            'days'=> $e->sphol_ot,
            'amount' => $e->sphol_ot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Night Diff. (Hrs)',
            'days'=> $e->sphol_nd,
            'amount' => $e->sphol_nd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Rest Day (Hrs)',
            'days'=> $e->sphol_rd,
            'amount' => $e->sphol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Rest Day O.T. (Hrs)',
            'days'=> $e->sphol_rdot,
            'amount' => $e->sphol_rdot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Night Diff O.T. (Hrs)',
            'days'=> $e->sphol_ndot,
            'amount' => $e->sphol_ndot_amount
        ]);
        
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday R.D. N.D. (Hrs)',
            'days'=> $e->sphol_rdnd,
            'amount' => $e->sphol_rdnd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday R.D. N.D. O.T. (Hrs)',
            'days'=> $e->sphol_rdndot,
            'amount' => $e->sphol_rdndot_amount
        ]);
        
        $total = $e->sphol_count_amount + $e->sphol_hrs_amount + $e->sphol_ot_amount + $e->sphol_nd_amount + $e->sphol_rd_amount + $e->sphol_rdot_amount + $e->sphol_ndot_amount +$e->sphol_rdnd_amount +$e->sphol_rdndot_amount;
        
        return array('list'=>collect($earnings),'total'=> $total );
    }

    public function dblLegHol($e)
    {
        $earnings = [];
        array_push($earnings, (object) [
            'name' => 'Double Legal Holiday Pay',
            'days'=> $e->dblhol_count,
            'amount' => $e->dblhol_count_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. (Hrs)',
            'days'=> $e->dblhol_hrs,
            'amount' => $e->dblhol_hrs_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. O.T. (Hrs)',
            'days'=> $e->dblhol_ot,
            'amount' => $e->dblhol_ot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Night Diff. (Hrs)',
            'days'=> $e->dblhol_nd,
            'amount' => $e->dblhol_nd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Rest Day (Hrs)',
            'days'=> $e->dblhol_rd,
            'amount' => $e->dblhol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Rest Day O.T. (Hrs)',
            'days'=> $e->dblhol_rdot,
            'amount' => $e->dblhol_rdot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Night Diff O.T. (Hrs)',
            'days'=> $e->dblhol_ndot,
            'amount' => $e->dblhol_ndot_amount
        ]);
        
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. R.D. N.D. (Hrs)',
            'days'=> $e->dblhol_rdnd,
            'amount' => $e->dblhol_rdnd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. R.D. N.D. O.T. (Hrs)',
            'days'=> $e->dblhol_rdndot,
            'amount' => $e->dblhol_rdndot_amount
        ]);
        
        $total = $e->dblhol_count_amount + $e->dblhol_hrs_amount + $e->dblhol_ot_amount + $e->dblhol_nd_amount + $e->dblhol_rd_amount + $e->dblhol_rdot_amount + $e->dblhol_ndot_amount +$e->dblhol_rdnd_amount +$e->dblhol_rdndot_amount;
        
        return array('list'=>collect($earnings),'total'=> $total );
    }

    public function restDay($e){
      
        $earnings=[];
        
        array_push($earnings, (object) [
            'name' => 'Rest Day (Hrs)',
            'days'=> $e->rd_hrs,
            'amount' => $e->rd_hrs_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day O.T. (Hrs)',
            'days'=> $e->rd_ot,
            'amount' => $e->rd_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day Night Diff. (Hrs)',
            'days'=> $e->rd_nd,
            'amount' => $e->rd_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day Night Diff. O.T. (Hrs)',
            'days'=> $e->rd_ndot,
            'amount' => $e->rd_ndot_amount
        ]);

        return array('list'=>collect($earnings),'total'=> $e->rd_hrs_amount + $e->rd_ot_amount + $e->rd_nd_amount + $e->rd_ndot_amount );
    }

    public function allowances($e)
    {
        $earnings = [];
        
        
        array_push($earnings, (object) [
            'name' => 'Daily Allowance',
            'days'=> '',
            'amount' => $e->daily_allowance
        ]);

        array_push($earnings, (object) [
            'name' => 'Semi Monthly Allowance',
            'days'=> '',
            'amount' => $e->semi_monthly_allowance
        ]);

        return array('list'=>collect($earnings),'total'=> $e->daily_allowance + $e->semi_monthly_allowance );
    }

    public function regEarnings($e){
        $earnings = [];

        array_push($earnings, (object) [
            'name' => 'Overtime (Hrs)',
            'days'=> $e->reg_ot,
            'amount' => $e->reg_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Night Diff (Hrs)',
            'days'=> $e->reg_nd,
            'amount' => $e->reg_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Night Diff O.T.',
            'days'=> $e->reg_ndot,
            'amount' => $e->reg_ndot_amount
        ]);

        return collect($earnings);
    }

    public function slvl($e){
        $earnings = [];
        array_push($earnings, (object) [
            'name' => 'Vacation Leave',
            'days'=> $e->vl_wpay,
            'amount' => $e->vl_wpay_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Sick Leave',
            'days'=> $e->sl_wpay,
            'amount' => $e->sl_wpay_amount
        ]);
        
        return collect($earnings);
    }

    public function paySlipGovLoan($period_id,$biometric_id)
    {
        //SELECT SUM(amount) AS amount FROM posted_loans WHERE biometric_id = AND period_id = 0;
        $loantotal = 0;
        // $result = $this->model->select(DB::raw("line_id,description,amount"))
        //                         ->from('posted_loans')
        //                         ->join('loan_types','loan_types.id','=','deduction_id')
        //                         ->where('biometric_id',$biometric_id)
        //                         ->where('period_id',$period_id)
        //                         ->get();
        /*
                SELECT loan_types.description,SUM(posted_loans.amount) AS total_pay,total_amount-SUM(posted_loans.amount) AS balance FROM deduction_gov_loans 
                INNER JOIN posted_loans ON 
        deduction_gov_loans.id = posted_loans.deduction_id AND
        deduction_gov_loans.biometric_id =posted_loans.biometric_id AND
        deduction_gov_loans.deduction_type = posted_loans.deduction_type 
        INNER JOIN loan_types ON loan_types.id = deduction_gov_loans.deduction_type
        GROUP BY description,total_amount
        */

        $result = $this->model->select(DB::raw("loan_types.description,posted_loans.amount,total_amount-SUM(posted_loans.amount) AS balance"))
        ->from('deduction_gov_loans')
        ->join('posted_loans',function($join){
            $join->on('deduction_gov_loans.id','=','posted_loans.deduction_id');
            $join->on('deduction_gov_loans.biometric_id','=','posted_loans.biometric_id');
            $join->on('deduction_gov_loans.deduction_type','=','posted_loans.deduction_type');
        })
        ->join('loan_types','loan_types.id','=','deduction_gov_loans.deduction_type')
        ->where('posted_loans.biometric_id',$biometric_id)
        ->where('posted_loans.period_id',$period_id)
        ->groupByRaw('description,total_amount')
        ->get();

        foreach($result as $loan){
            $loantotal += $loan->amount;
        }

        return array(
            'total' => $loantotal,
            'list' => $result
        );
        
    }

    public function otherEearnings($period_id,$biometric_id)
    {
        $total = 0;
        $query = "SELECT description,amount FROM posted_fixed_compensations 
        INNER JOIN compensation_types ON posted_fixed_compensations.compensation_type = compensation_types.id
        WHERE posted_fixed_compensations.biometric_id = $biometric_id AND period_id =  $period_id
        UNION ALL
        SELECT description,amount FROM posted_other_compensations 
        INNER JOIN compensation_types ON posted_other_compensations.compensation_type = compensation_types.id
        WHERE posted_other_compensations.biometric_id = $biometric_id AND period_id = $period_id";

        $result = DB::select($query); 

        foreach($result as $earn)
        {
            $total += $earn->amount;
        }
        
        return array(
            'total' => $total,
            'list' => $result
        );
    }

    public function fixedDeduction($period_id,$biometric_id)
    {
        $total = 0;

        $query = "SELECT description,amount FROM posted_fixed_deductions 
        INNER JOIN deduction_types ON deduction_type = deduction_types.id
        WHERE biometric_id = $biometric_id AND period_id = $period_id
        UNION ALL
        SELECT description,amount FROM posted_onetime_deductions 
        INNER JOIN deduction_types ON deduction_type = deduction_types.id
        WHERE biometric_id = $biometric_id AND period_id = $period_id";

        $result = DB::select($query); 

        foreach($result as $earn)
        {
            $total += $earn->amount;
        }
        
        return array(
            'total' => $total,
            'list' => $result
        );

    }

    public function installments($period_id,$biometric_id)
    {
        $loantotal = 0;
        $result = $this->model->select(DB::raw("deduction_types.description,posted_installments.amount,total_amount-SUM(posted_installments.amount) AS balance"))
        ->from('deduction_installments')
        ->join('posted_installments',function($join){
            $join->on('deduction_installments.id','=','posted_installments.deduction_id');
            $join->on('deduction_installments.biometric_id','=','posted_installments.biometric_id');
            $join->on('deduction_installments.deduction_type','=','posted_installments.deduction_type');
        })
        ->join('deduction_types','deduction_types.id','=','deduction_installments.deduction_type')
        ->where('posted_installments.biometric_id',$biometric_id)
        ->where('posted_installments.period_id',$period_id)
        ->groupByRaw('description,total_amount')
        ->get();

        foreach($result as $loan){
            $loantotal += $loan->amount;
        }

        return array(
            'total' => $loantotal,
            'list' => $result
        );
        
    }



}

/*
SELECT employees.biometric_id,lastname,firstname,suffixname FROM employees 
INNER JOIN payrollregister_posted_s ON employees.biometric_id = payrollregister_posted_s.biometric_id
division_id dept_id
*/