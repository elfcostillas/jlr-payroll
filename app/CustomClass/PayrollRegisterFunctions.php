<?php

namespace App\CustomClass;

use App\Models\EmployeeFile\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PayrollRegisterFunctions
{

    public function mainQuery()
    {

        $result = DB::table('employees');

        if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
            $result->where('employees.emp_level','<',5);
        }else{
            $result->where('employees.emp_level','>=',5);
        }

        $result->join($this->db_table,'employees.biometric_id','=',$this->db_table.'.biometric_id');
        $result->where($this->db_table.'.period_id','=',$this->period->id);
        $result->where($this->db_table.'.user_id','=',Auth::user()->id);

        return $result;
    }
 
    public function getPeriod($id)
    {
       
        return DB::table('payroll_period')->select()
        ->where('id','=',$id)
        ->first();
    }

    public function getLocations()
    {
       
        return $this->mainQuery()->join('locations','locations.id','=','employees.location_id')
                ->select('locations.id','locations.location_altername2')
                ->distinct()
                ->orderBy('locations.id','ASC')
                ->get();
    }

    public function getEmployees($location)
    {
        return $this->mainQuery()
            ->leftJoin('divisions','divisions.id','=','employees.division_id')
            ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
            ->leftJoin('departments','departments.id','=','employees.dept_id')
            ->where('employees.location_id','=',$location->id)
            ->get();
    }

    public function getDivisionByLocation($location)
    {
        return $this->mainQuery()->leftJoin('divisions','divisions.id','=','employees.division_id')
            ->select('divisions.id','div_code','div_name')
            ->distinct()
            ->leftJoin('departments','departments.id','=','employees.dept_id')
            ->where('employees.location_id','=',$location->id)
            ->get();
    }

    public function getDeptByDivisionAndLocation($location,$division)
    {
        return $this->mainQuery()
                ->leftJoin('divisions','divisions.id','=','employees.division_id')
                ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
                ->leftJoin('departments','departments.id','=','employees.dept_id')
                ->select('departments.id','departments.dept_code','departments.dept_name')
                ->distinct()
                ->where('employees.location_id','=',$location->id)
                ->where('divisions.id','=',$division->id)
                ->get();
    }

    public function getEmployeesByDeotDivAndLocation($location,$division,$department)
    {
        return $this->mainQuery()
                ->leftJoin('divisions','divisions.id','=','employees.division_id')
                ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
                ->leftJoin('departments','departments.id','=','employees.dept_id')
                ->select()
                ->where('employees.location_id','=',$location->id)
                ->where('divisions.id','=',$division->id)
                ->where('employees.dept_id','=',$department->id)
                ->get();
    }

    public function getEmployeesByDeptAndDivision($division,$department)
    {
        return $this->mainQuery()
                ->leftJoin('divisions_sub','divisions_sub.id','=','employees.sub_division')
                ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
                ->leftJoin('sub_dept','sub_dept.id','=','employees.sub_dept')
                ->select()
                ->where('divisions_sub.id','=',$division->id)
                ->where('employees.sub_dept','=',$department->id)
                ->orderBy('lastname','asc')
                ->orderBy('firstname','asc')
                ->get();
    }

    public function querySum()
    {
        $qry = '';
        $cols_with_value = [];

        $cols = Schema::getColumnListing($this->db_table);

        $except = ['line_id',
                    'id_daily',
                    'biometric_id',
                    'period_id',
                    'basic_salary',
                    'daily_rate',
                    'is_daily'];

        foreach($cols as $col)
        {
            if(!in_array($col,$except)) {
                if($qry != ''){
                    $qry .= ', sum('. $col .') as '.$col;            
                }else{
                    $qry .= ' sum('. $col .') as '.$col;       
                }
            }
        }

        if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
            $type = 'confi';
        }else{
            $type = 'non-confi';
        }

        $col_to_filter = DB::table($this->db_table)
            ->select(DB::raw($qry))
            ->where('emp_level','=',$type)
            ->where('period_id','=',$this->period->id)
            ->first();

       

        foreach($col_to_filter as $key => $value){
            if($value > 0){
                array_push($cols_with_value,$key);
            }
        }

        return $cols_with_value;
    }

    public function getColsByType($type)
    {
        return DB::table('payroll_column_header')
                ->where('col_type','=',$type)
                ->whereIn('var_name',$this->cols_with_value)
                // ->orderBy('sort','asc')
                ->orderBy('id','asc')
                ->get();
    }

    public function getCompensationTypeCols($table)
    {
        $result =  DB::table($table)
            ->join('employees',$table.'.biometric_id','=','employees.biometric_id')
            ->join('compensation_types','compensation_types.id','=',$table.'.compensation_type')
            ->select(DB::raw("$table.compensation_type,compensation_types.description"))
            ->distinct()
            ->where($table.'.period_id','=',$this->period->id)
            ->where('user_id','=',Auth::user()->id);

        if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
            $result->where('employees.emp_level','<',5);
        }else{
            $result->where('employees.emp_level','>=',5);
        }

        return $result->get();
    }

    public function otherEarnings($employee,$period)
    {
      
        $earning_array = [];

        $others = DB::table('unposted_other_compensations')->select('compensation_type','amount')
        ->where([['biometric_id','=',$employee->biometric_id],['period_id','=',$period->id]])
        ->where('user_id','=',Auth::user()->id);

        $fixed = DB::table('unposted_fixed_compensations')->select('compensation_type','amount')
        ->where([['biometric_id','=',$employee->biometric_id],['period_id','=',$period->id]])
        ->where('user_id','=',Auth::user()->id)
        ->unionAll($others);

        $earnings = DB::table('compensation_types')
                    ->select('description','compensation_type','amount')
                    ->joinSub($fixed,'earnings',function($join){
                        $join->on('earnings.compensation_type','=','compensation_types.id');
                    })->orderBy('compensation_type')->get();
        
        foreach($earnings as $earn){
            $earning_array[$earn->compensation_type] = $earn->amount;
        }
        
        return $earning_array;
       
    }

       public function getDeductions($biometric_id,$period_id)
    {   
        $ded_array = [];

      
        if($this->payroll_status == 'unposted')
        {
            //$table = ['unposted_fixed_deductions','unposted_installments','unposted_onetime_deductions'];
            $onetime = DB::table("unposted_onetime_deductions")->select('deduction_type','amount')
            ->where('user_id','=',Auth::user()->id)
            ->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);

            $fixed = DB::table("unposted_fixed_deductions")->select('deduction_type','amount')
            ->where('user_id','=',Auth::user()->id)
            ->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]]);

            $install = DB::table("unposted_installments")->select('deduction_type','amount')
            ->where('user_id','=',Auth::user()->id)
            ->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
            ->unionAll($onetime)->unionAll($fixed);

        }else{

        }


        $deductions = DB::table('deduction_types')
                        ->select('description','deduction_type','amount')
                        ->joinSub($install,'deductions',function($join){
                            $join->on('deductions.deduction_type','=','deduction_types.id');
                        })->orderBy('deduction_type')->get();

      
        foreach($deductions as $deduction){
           
            if(array_key_exists($deduction->deduction_type,$ded_array)){
                $ded_array[$deduction->deduction_type] += $deduction->amount;
            }else{
                $ded_array[$deduction->deduction_type] = 0;
                $ded_array[$deduction->deduction_type] += $deduction->amount;
            }
        }

        return $ded_array;

        
    }

    public function getGovLoans($biometric_id,$period_id)
    {
      
        $govLoan = [];
        $loan = DB::table('unposted_loans')->select('id','description','amount')
        ->join('loan_types','deduction_type','=','loan_types.id')
        ->where([['biometric_id','=',$biometric_id],['period_id','=',$period_id]])
        ->where('user_id','=',Auth::user()->id)
        ->orderBy('deduction_type')->get();

        foreach($loan as $l)
        {
            if(array_key_exists($l->id,$govLoan)){
                $govLoan[$l->id] += $l->amount;
            }else{
                $govLoan[$l->id] = 0;
                $govLoan[$l->id] += $l->amount;
            }
        }
       
        return $govLoan;
        
        //return $loan;
    }

    public function computeTotalByDeptT($data,$key){
        dd($data,$key);
    }

    public function computeTotalByDivision($data,$key){
        $total = 0;

        foreach($data->departments as $department)
        {
            $total += $this->computeTotalByDept($department,$key);
        }

        return $total;
    }

    public function computeTotalByDept($data,$key){

        $total = 0;
        
        if(isset($data->employees)){
            foreach ($data->employees as $emp)
            {
                if(is_object($key)){
                    $total += $emp->{$key->var_name};
                }else{
                    $total += $emp->{$key};
                }
                
            }
        }else{
            dd($data);
        }
        

        return $total;
    }

    public function computeTotalOtherEarningByDivision($data,$key)
    {
        $total = 0;
        
        foreach($data->departments as $department)
        {
            $total += $this->computeTotalOtherEarningByDept($department,$key);
        }

        return $total;
    }

    public function  computeTotalOtherEarningByDept($data,$key) {
        $total = 0;
        foreach ($data->employees as $emp)
        {
           
            if($emp->other_earning)
            {
                if(array_key_exists($key->compensation_type,$emp->other_earning))
                {
                    $total += $emp->other_earning[$key->compensation_type];
                }else{
                    $total += 0;
                }

            }
        }

        return $total;
    }

    public function computeTotalDeductionsByDivision($data,$key)
    {
        $total = 0;

        foreach ($data->departments as $department)
        {
           
            $total += $this->computeTotalDeductionsByDept($department,$key);
        }

        return $total;
    }

    public function computeTotalDeductionsByDivisionV2($data,$key)
    {
        $total = 0;

        // foreach ($data->departments as $department)
        // {
           
        //     $total += $this->computeTotalDeductionsByDept($department,$key);
        // }

        foreach ($data->data as $division)
        {
            foreach($division->departments as $department)
            {
                $total += $this->computeTotalDeductionsByDept($department,$key);
            }
           
        }

        return $total;
    }

    public function computeTotalByDivisionV2($data,$key)
    {
        $total = 0;

        
        foreach ($data->data as $division)
        {
            foreach($division->departments as $department)
            {
                $total += $this->computeTotalByDept($department,$key);
            }
           
        }

        return $total;
    }

    public function computeTotalOtherEarningByDivisionV2($data,$key)
    {
        $total = 0;
        
        // foreach($data->departments as $department)
        // {
        //     $total += $this->computeTotalOtherEarningByDept($department,$key);
        // }

        foreach ($data->data as $division)
        {
            foreach($division->departments as $department)
            {
                $total += $this->computeTotalOtherEarningByDept($department,$key);
            }
           
        }

        return $total;
    }

    

    public function computeTotalDeductionsByLocation($data,$key)
    {
        $total = 0;
        foreach($data->divisions as $division)
        {
            $total += $this->computeTotalDeductionsByDivision($division,$key); 
        }  
        return $total;
    }

    public function computeTotalDeductionsOverall($data,$key)
    {
        $total = 0;
        foreach($data->data as $location)
        {
            $total += $this->computeTotalDeductionsByLocation($location,$key);
        }
        return $total;
    }

    public function computeTotalDeductionsByDept($data,$key)
    {
        $total = 0;

        foreach ($data->employees as $emp)
        {
           
            if($emp->deductions && array_key_exists($key->id,$emp->deductions))
            {
                $total += $emp->deductions[$key->id];
            }
        }

        return $total;
    }

    public function computeTotalDeductionsByDeptV2($data,$key)
    {
        $total = 0;

        foreach ($data->employees as $emp)
        {
           
            if($emp->deductions && array_key_exists($key->id,$emp->deductions))
            {
                $total += $emp->deductions[$key->id];
            }
        }

        return $total;
    }

    public function computeTotalLoanByDivision($data,$key)
    {
        $total = 0;

        // dd($data-.Department,$key);

        foreach ($data->departments as $department)
        {
            $total += $this->computeTotalLoansByDept($department,$key);
        }
        return $total;
    }

    public function computeTotalLoanByDivisionV2($data,$key)
    {
        $total = 0;

        // dd($data-.Department,$key);
        foreach ($data->data as $division)
        {
            foreach($division->departments as $department)
            {
                $total += $this->computeTotalLoansByDept($department,$key);
            }
           
        }
        return $total;
    }

    public function computeTotalLoanByLocation($data,$key)
    {   
        $total = 0;
        foreach($data->divisions as $division)
        {
            $total += $this->computeTotalLoanByDivision($division,$key); 
        }   

        return $total;
    }

    public function computeTotalLoanOverAll($data,$key)
    {
        $total = 0;
        // dd($data->data,$key);
        foreach($data->data as $location)
        {
            $total += $this->computeTotalLoanByLocation($location,$key);
        }

        return $total;
    }

    public function computeTotalByLocation($data,$key)
    {
        $total = 0;
            foreach($data->divisions as $division)
            {
                $total += $this->computeTotalByDivision($division,$key); 
            }   
        return $total;
    }

    public function computeOverAll($data,$key)
    {
        $total = 0;
        // dd($data->data,$key);
        foreach($data->data as $location)
        {
            $total += $this->computeTotalByLocation($location,$key);
        }

        return $total;
    }

    public function computeOtherEarningsByLocation($data,$key)
    {
        $total = 0;
        foreach($data->divisions as $division)
        {
            $total += $this->computeTotalOtherEarningByDivision($division,$key); 
        } 
        return $total;
    }

    public function computeOtherEarningsOverAll($data,$key)
    {
        $total = 0;
        foreach($data->data as $location)
        {
            $total += $this->computeOtherEarningsByLocation($location,$key);
        }
        return $total;
    }


    public function computeTotalLoansByDept($data,$key)
    {
        $total = 0;

        foreach ($data->employees as $emp)
        {
        //   dd($emp->gov_loans,$key); 
         if($emp->gov_loans && array_key_exists($key->id,$emp->gov_loans))
        {
            $total += $emp->gov_loans[$key->id];
        }
          
        }

        return $total;
    }

    public function getGovLoansLabel() {
        if($this->payroll_status == 'unposted')
        {
            $table = 'unposted_loans';
        }else{
            $table = 'posted_loans';
        }

        if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
            $result = DB::table($table)->join('loan_types','loan_types.id','=',"$table.deduction_type")
                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                ->where("$table.period_id",'=',$this->period->id)
                ->where('employees.emp_level','<',5)
                ->where('user_id','=',Auth::user()->id)
                ->select('loan_types.id','loan_types.description')->distinct();
        }else{
            $result = DB::table($table)->join('loan_types','loan_types.id','=',"$table.deduction_type")
                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                ->where("$table.period_id",'=',$this->period->id)
                ->where('employees.emp_level','>=',5)
                ->where('user_id','=',Auth::user()->id)
                ->select('loan_types.id','loan_types.description')->distinct();
        }

        return $result->get();
    }

    public function getDeductionLabel()
    {
        $table = null;

        if($this->payroll_status == 'unposted')
        {
            $table = ['unposted_fixed_deductions','unposted_installments','unposted_onetime_deductions'];
        }else{

        }

        if($table != null){
            
            $result = null;
            $final = null;

            foreach($table as $key => $table){
              
                if($result == null){

                       if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
                            $result = DB::table($table)->join('deduction_types','deduction_types.id','=',"$table.deduction_type")
                                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                                ->where("$table.period_id",'=',$this->period->id)
                                ->where('employees.emp_level','<',5)
                                ->where('user_id','=',Auth::user()->id)
                                ->select('deduction_types.id','deduction_types.description')->distinct();
                        }else{
                            $result = DB::table($table)->join('deduction_types','deduction_types.id','=',"$table.deduction_type")
                                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                                ->where("$table.period_id",'=',$this->period->id)
                                ->where('employees.emp_level','>=',5)
                                ->where('user_id','=',Auth::user()->id)
                                ->select('deduction_types.id','deduction_types.description')->distinct();
                        }
                  
                }else{

                        if(get_class($this) == 'App\CustomClass\PayrollRegisterConfi'){
                            $final = $result->union(DB::table($table)->join('deduction_types','deduction_types.id','=',"$table.deduction_type")
                                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                                ->where("$table.period_id",'=',$this->period->id)
                                ->where('employees.emp_level','<',5)
                                ->where('user_id','=',Auth::user()->id)
                                ->select('deduction_types.id','deduction_types.description')->distinct());
                        }else{
                            $result = $result->union(DB::table($table)->join('deduction_types','deduction_types.id','=',"$table.deduction_type")
                                ->join('employees','employees.biometric_id','=',"$table.biometric_id")
                                ->where("$table.period_id",'=',$this->period->id)
                                ->where('employees.emp_level','>=',5)
                                ->where('user_id','=',Auth::user()->id)
                                ->select('deduction_types.id','deduction_types.description')->distinct());
                        }
                   

                }
               
            }

            return $final->get();
        }
    }

    public function getCounts()
    {
        $result = $this->mainQuery()
         ->leftJoin('divisions','divisions.id','=','employees.division_id')
        ->select(DB::raw("divisions.id,divisions.div_name,count(employees.biometric_id) as head_count"))
        ->orderBy('divisions.id','ASC')
        ->groupBy('divisions.id');

        return $result->get();
    }

    public function getCountsV2()
    {
        $result = $this->mainQuery()
         ->leftJoin('divisions_sub','divisions_sub.id','=','employees.sub_division')
        ->select(DB::raw("divisions_sub.id,divisions_sub.div_name,count(employees.biometric_id) as head_count"))
        ->orderBy('divisions_sub.id','ASC')
        ->groupBy('divisions_sub.id');

        return $result->get();
    }

    public function total_pay_per_dept()
    {
        $result = $this->mainQuery()->leftJoin('sub_dept','employees.sub_dept','=','sub_dept.id')
            ->distinct()
            ->select(DB::raw("sub_dept.id, sub_dept.dept_label,sum(gross_pay) as gross_pay, sum(gross_total) as gross_total, sum(net_pay) as net_pay"))
            ->groupBy('sub_dept.id')
            ->orderBy('sub_dept.id','asc')
            ->get();

        return $result;
    }

    public function summaryDeptByLocation()
    {
        $locations = $this->mainQuery()->leftJoin('locations','employees.location_id','locations.id')
            ->select('locations.id','locations.location_altername2')
            ->distinct()
            ->orderBy('locations.id','asc')
            ->get();

        $departments = $this->mainQuery()->leftJoin('sub_dept','employees.sub_dept','=','sub_dept.id')
            ->distinct()
            ->select(DB::raw("sub_dept.id, sub_dept.dept_label"))
            ->orderBy('sub_dept.id','asc')
            ->get();

        $totals_by_loc = [];
        $totals_by_dept = [];
        $over_all = 0;

        foreach($locations as $loc)
        {
            foreach($departments as $dept)
            {
                $employee =  $this->mainQuery()
                    ->where('employees.sub_dept','=',$dept->id)
                    ->where('employees.location_id','=',$loc->id)
                    ->select(DB::raw("count(employees.id) as pax"))
                    ->first();

                $data[$dept->id][$loc->id] = $employee->pax;

                if(array_key_exists($loc->id, $totals_by_loc)){
                    $totals_by_loc[$loc->id] += $employee->pax;
                }else{
                    $totals_by_loc[$loc->id] = $employee->pax;
                }

                if(array_key_exists($dept->id, $totals_by_dept)){
                    $totals_by_dept[$dept->id] += $employee->pax;
                }else{
                    $totals_by_dept[$dept->id] = $employee->pax;
                }

                $over_all += $employee->pax;

            }
        }

        return [
            'x' => $locations,
            'y' => $departments,
            'data' => $data,
            'totals_by_loc' => $totals_by_loc,
            'totals_by_dept' => $totals_by_dept,
            'over_all' => $over_all
        ];
    }

    public function countPerJobTitleLocation()
    {
        $locations = DB::table('locations')->get();

        foreach($locations as $location)
        {
            $data = $this->countPerJobTitle($location);

            $location->data = $data;
        }

        return $locations;
    }

    public function countPerJobTitle($location)
    {
        return $this->mainQuery()
                ->leftJoin('divisions_sub','divisions_sub.id','=','employees.sub_division')
                ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
                ->leftJoin('sub_dept','sub_dept.id','=','employees.sub_dept')
                ->select(DB::raw("dept_label,job_title_name,count(employees.id) as pax"))
                ->where('employees.location_id','=',$location->id)
                ->groupBy('dept_label')
                ->groupBy('job_title_name')
                ->orderBy('dept_label','asc')
                ->orderBy('job_title_name','asc')
                ->get();
    }

    public function otSummary()
    {
        $result = $this->mainQuery()->get();

        $data = [
            'Less than 10 Hrs' => 0,
            '10 Hrs' => 0,
            '20 Hrs' => 0,
            '30 Hrs' => 0,
            '40 Hrs' => 0,
            '50 Hrs' => 0,
            '60 Hrs' => 0,
            '70 Hrs' => 0,
            '80 Hrs' => 0,
            '90 Hrs' => 0,
            '100+ Hrs' => 0,
        ];

        foreach($result as $employee)
        {
            // dd($employee->reg_ot);
            if($employee->reg_ot > 1 && $employee->reg_ot < 10)
            {
                $data['Less than 10 Hrs'] += 1;
            }

            if($employee->reg_ot >= 10 && $employee->reg_ot < 20)
            {
                $data['10 Hrs'] += 1;
            }

            if($employee->reg_ot >= 20 && $employee->reg_ot < 30)
            {
                $data['20 Hrs'] += 1;
            }

            if($employee->reg_ot >= 30 && $employee->reg_ot < 40)
            {
                $data['30 Hrs'] += 1;
            }
             if($employee->reg_ot >= 40 && $employee->reg_ot < 50)
            {
                $data['40 Hrs'] += 1;
            }

            if($employee->reg_ot >= 50 && $employee->reg_ot < 60)
            {
                $data['50 Hrs'] += 1;
            }

            if($employee->reg_ot >= 60 && $employee->reg_ot < 70)
            {
                $data['60 Hrs'] += 1;
            }

            if($employee->reg_ot >= 70 && $employee->reg_ot < 80)
            {
                $data['70 Hrs'] += 1;
            }
            if($employee->reg_ot >= 80 && $employee->reg_ot < 90)
            {
                $data['80 Hrs'] += 1;
            }

            if($employee->reg_ot >= 90 && $employee->reg_ot < 100)
            {
                $data['90 Hrs'] += 1;
            }
           
            if($employee->reg_ot >= 100 )
            {
                $data['100+ Hrs'] += 1;
            }
        }

        

        return $data;
    }

    public function otByDeptJobtitle($key)
    {
            // 'less 10 Hrs' => 0,
            // '10 Hrs' => 0,
            // '20 Hrs' => 0,
            // '30 Hrs' => 0,
            // '40 Hrs' => 0,
            // '50 Hrs' => 0,
            // '60 Hrs' => 0,
            // '70 Hrs' => 0,
            // '80 Hrs' => 0,
            // '90 Hrs' => 0,
            // '100+ Hrs' => 0,

            $qry = $this->mainQuery()
                ->leftJoin('divisions_sub','divisions_sub.id','=','employees.sub_division')
                ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
                ->leftJoin('sub_dept','sub_dept.id','=','employees.sub_dept')
                ->select(DB::raw("divisions_sub.div_code,dept_label,job_title_name,count(employees.id) as pax"))
                ->groupBy('dept_label')
                ->groupBy('job_title_name')
                ->groupBy('div_code')
                ->orderBy('dept_label','asc')
                ->orderBy('div_code','asc')
                ->orderBy('job_title_name','asc');

            switch($key){
                case 'Less than 10 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',1);
                        $query->where('reg_ot','<',10);
                    });
                    break;

                case '10 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',10);
                        $query->where('reg_ot','<',20);
                    });
                break;

                case '20 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',20);
                        $query->where('reg_ot','<',30);
                    });
                break;

                case '30 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',30);
                        $query->where('reg_ot','<',40);
                    });
                break;

                case '40 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',40);
                        $query->where('reg_ot','<',50);
                    });
                break;

                case '50 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',50);
                        $query->where('reg_ot','<',60);
                    });
                break;

                case '60 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',60);
                        $query->where('reg_ot','<',70);
                    });
                break;

                case '70 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',70);
                        $query->where('reg_ot','<',80);
                    });
                break;

                case '80 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',80);
                        $query->where('reg_ot','<',90);
                    });
                break;

                case '90 Hrs':
                    $result = $qry->where(function($query){
                        $query->where('reg_ot','>',90);
                        $query->where('reg_ot','<',100);
                    });
                break;

                case '100+ Hrs':
                    $result = $qry->where('reg_ot','>',100);
                break;
            }

            return $result->get();
    }

    public function otMoreThan50hrs()
    {
        $qry = $this->mainQuery()
            ->leftJoin('divisions_sub','divisions_sub.id','=','employees.sub_division')
            ->leftJoin('job_titles','employees.job_title_id','=','job_titles.id')
            ->leftJoin('sub_dept','sub_dept.id','=','employees.sub_dept')
            ->select(DB::raw("divisions_sub.div_code,count(employees.id) as pax"))
            ->where('reg_ot','>=',50)
            ->groupBy('div_code');
        
        return $qry->get();
    }


}

/*
->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id')
		->leftJoin('job_titles','employees.job_title_id','=','job_titles.id');

SELECT DISTINCT unposted_fixed_compensations.compensation_type,compensation_types.description FROM `unposted_fixed_compensations` 
INNER JOIN `employees` ON `unposted_fixed_compensations`.`biometric_id` = `employees`.`biometric_id` 
INNER JOIN `compensation_types` ON `compensation_types`.`id` = `unposted_fixed_compensations`.`compensation_type` 
WHERE `unposted_fixed_compensations`.`period_id` = 62 AND `user_id` = 1


SELECT DISTINCT unposted_fixed_compensations.compensation_type,compensation_types.description FROM unposted_fixed_compensations
INNER JOIN employees ON unposted_fixed_compensations.biometric_id = employees.biometric_id
INNER JOIN compensation_types ON compensation_types.id = unposted_fixed_compensations.compensation_type
WHERE unposted_fixed_compensations.period_id = 62
AND employees.emp_level < 5
AND user_id = 26;

        */