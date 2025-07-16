<?php

namespace App\CustomClass;

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

        $col_to_filter = DB::table($this->db_table)->select(DB::raw($qry))->first();

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
                ->orderBy('sort','asc')
                ->get();
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

        */