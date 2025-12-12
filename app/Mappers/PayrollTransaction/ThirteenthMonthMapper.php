<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Models\PayrollTransaction\ThirteenthMonthEmployee;
use App\Models\PayrollTransaction\ThirteenthMonthJLREmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use App\Models\PayrollTransaction\ThirteenthMonth;

class ThirteenthMonthMapper extends AbstractMapper
{
    //

    protected $modelClassName = 'App\Models\PayrollTransaction\ThirteenthMonth';

    protected $rules = [
    	
    ];

    function getYears()
    {   
        //SELECT pyear FROM payroll_year 

        // $result = DB::table("payroll_year")
        //     ->select(DB::raw("pyear as text,pyear as value"))->get();
        // return $result;
        // select distinct(year(date_from)) from payroll_period

        $result = DB::table('payroll_period')
            ->select(DB::raw("distinct(year(date_from)) as text,year(date_from) as pyear"))
            ->orderBy('date_from','desc')
            ->get();

        return $result;
    }

    public function empQuery()
    {
        $result = DB::table('employees')
            ->where('employees.exit_status',1);

        return $result;
    }

    public function empQueryR()
    {
        $result = DB::table('employees')
            ->where('employees.exit_status','!=',1);

        return $result;
    }

    public function getConfi()
    {
        $result = $this->empQuery()
            ->where('emp_level','<',5);
        return $result;
    }

    
    public function getConfiR($year,$months)
    {
        $result = $this->empQueryR()
            ->where('emp_level','<',5);
        return $result;
    }

    function buildData($year)
    {
        
        // dd(Auth::user()->id);
        $locations = $this->baseQuery()->select(DB::raw("employees.location_id,location_name"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->orderBy('location_id','ASC')
                ->get();
        
        DB::table('thirteenth_month_sg')->where('pyear' ,$year)->where('user_id',Auth::user()->id)->where('stat','=','DRAFT')->delete();
        
        foreach($locations as $location)
        {
            $employee_array = [];

            $employees = $this->baseQuery()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();

            foreach($employees as $employee)
            {
                // array_push($employee_array,)
                
                $e = $this->employeeFactory($employee,$year);

                if($e){
                    // DB::table('thirteenth_month_sg')->updateOrInsert([
                    //         'pyear' => $year,
                    //         'biometric_id' => $e->getBiometricID() 
                    //     ],[
                    //         'net_pay' => $e->getNetPay()
                    //     ]);

                    DB::table('thirteenth_month_sg')->insert(['user_id' => Auth::user()->id,
                        'pyear' => $year,
                        'biometric_id' => $e->getBiometricID(),
                        'net_pay' => $e->getNetPay(),
                        'gross_pay' => $e->getGrossPay(),
                        'created_on' => now() ]);
                }

                

                array_push($employee_array,$e);
            }

            $location->employees = $employee_array;
           
        }

        $payrollPeriodOfYear = DB::table("payroll_period_weekly")
                ->select(DB::raw("id,date_from,date_to"))
                ->where("pyear",$year)
                ->get();

        return [
            'location' => $locations,
            'payroll_period' => $payrollPeriodOfYear
        ];
    }

    function buildDataInActive($year)
    {
        
        // dd(Auth::user()->id);
        $locations = $this->baseQueryI()->select(DB::raw("employees.location_id,location_name"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->orderBy('location_id','ASC')
                ->get();
        
        DB::table('thirteenth_month_sg')->where('pyear' ,$year)->where('user_id',Auth::user()->id)->where('stat','=','DRAFT')->delete();
        
        foreach($locations as $location)
        {
            $employee_array = [];

            $employees = $this->baseQueryI()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();

            foreach($employees as $employee)
            {
                // array_push($employee_array,)
                
                $e = $this->employeeFactory($employee,$year);

                if($e){

                    DB::table('thirteenth_month_sg')->insert(['user_id' => Auth::user()->id,
                        'pyear' => $year,
                        'biometric_id' => $e->getBiometricID(),
                        'net_pay' => $e->getNetPay(),
                        'gross_pay' => $e->getGrossPay(),
                        'created_on' => now() ]);
                }

                array_push($employee_array,$e);
            }

            $location->employees = $employee_array;
           
        }

        $payrollPeriodOfYear = DB::table("payroll_period_weekly")
                ->select(DB::raw("id,date_from,date_to"))
                ->where("pyear",$year)
                ->get();

        return [
            'location' => $locations,
            'payroll_period' => $payrollPeriodOfYear
        ];
    }

    public function buildDataJLRConfi($year,$months)
    {
        DB::table('thirteenth_month')
            ->where('pyear' ,$year)
            ->where('semi_annual' ,$months)
            ->where('user_id',Auth::user()->id)
            ->where('stat','=','DRAFT')
            ->delete();
        
        $employees = $this->getConfi()->get();
        
        foreach($employees as $employee)
        {
            $e = $this->employeeJLRFactory($employee,$year,$months);

            if($e){
                DB::table('thirteenth_month')->insert([
                    'user_id' => Auth::user()->id,
                    'pyear' => $year,
                    'semi_annual' => $months,
                    'biometric_id' => $e->getBiometricID(),
                    'net_pay' => $e->getNetPay(),
                    'gross_pay' => $e->getGrossPay(),
                    'created_on' => now(),
                    'emp_level' => 'confi']);
            }

            $employee->thirteenth_pay = $e;
        }

        return [
            'employees' => $employees,
            'payroll_periods' => $this->getPayrollPeriodsJLR($year,$months)->get(),
        ];

        
    }

    public function buildDataJLRConfiInActive($year,$months)
    {
        DB::table('thirteenth_month')
            ->where('pyear' ,$year)
            ->where('semi_annual' ,$months)
            ->where('user_id',Auth::user()->id)
            ->where('stat','=','DRAFT-R')
            ->delete();
        
        $employees = $this->getConfiR($year,$months)->get();
        
        foreach($employees as $employee)
        {
            $e = $this->employeeJLRFactory($employee,$year,$months);

            if($e){
                DB::table('thirteenth_month')->insert([
                    'user_id' => Auth::user()->id,
                    'pyear' => $year,
                    'semi_annual' => $months,
                    'biometric_id' => $e->getBiometricID(),
                    'net_pay' => $e->getNetPay(),
                    'gross_pay' => $e->getGrossPay(),
                    'created_on' => now(),
                    'stat' => 'DRAFT-R',
                    'emp_level' => 'confi']);
            }

            $employee->thirteenth_pay = $e;
        }

        return [
            'employees' => $employees,
            'payroll_periods' => $this->getPayrollPeriodsJLR($year,$months)->get(),
        ];

    }

    function employeeFactory($employee,$year)
    {
        $basic_pays = $this->basicPayQuery($employee,$year);
        return new ThirteenthMonthEmployee($employee,$basic_pays);
    }

    function getEncodedBasicPay($employee,$year,$months)
    {
        $payroll_periods = $this->getPayrollPeriodsJLR($year,$months);

        $basic_pays = DB::table('manual_basicpay')
            ->whereIn('period_id',$payroll_periods->pluck('id'))
            ->where('biometric_id',$employee->biometric_id)
            ->get();

        return $basic_pays;
    }

    function getEncodedBasicPayByMonth($employee,$payroll_periods)
    {
        $total_basic = 0;

        $basic_pays = DB::table('manual_basicpay')
            ->whereIn('period_id',$payroll_periods->pluck('id'))
            ->where('biometric_id',$employee->biometric_id)
            ->get();

        foreach($basic_pays as $basic_pay)
        {
            $total_basic += (float) $basic_pay->basic_pay;
        }

        return $total_basic;
    }

    public function buildMonthlyPay($employee,$year,$months)
    {
        $monthly_arr = [];

        foreach($months as $key => $value)
        {

            $payroll_periods = $this->getPayrollPeriodsByMonth($year,$key);
            
            $copmensations = DB::table('posted_other_compensations')
                ->whereIn('period_id',$payroll_periods->pluck('id'))
                ->where('biometric_id',$employee->biometric_id);
                
            $payrolls = DB::table('payrollregister_posted_s')
                    ->whereIn('period_id',$payroll_periods->pluck('id'))
                    ->where('biometric_id',$employee->biometric_id);
            
            $result = DB::table('payroll_period')
            ->leftJoinSub($payrolls,'subQuery',function($join){ 
                $join->on('payroll_period.id','=','subQuery.period_id');
            })
             ->leftJoinSub($copmensations,'subQuery2',function($join){ 
                $join->on('payroll_period.id','=','subQuery2.period_id');
            })
            ->select(DB::raw("sum(ifnull(subQuery.basic_pay,0.00)) 
                    + sum(ifnull(sl_wpay_amount,0.00))
                    + sum(ifnull(bl_wpay_amount,0.00))
                    + sum(ifnull(vl_wpay_amount,0.00))
                    + sum(ifnull(svl_amount,0.00))
                    + sum(ifnull(semi_monthly_allowance,0.00))
                    + sum(ifnull(leghol_count_amount,0.00))
                    + sum(ifnull(sphol_count_amount,0.00))
                    + sum(ifnull(subQuery2.amount,0.00))
                    as basic_pay"))
            ->whereIn('id',$payroll_periods->pluck('id'))
            ->first();

            $manual_input = $this->getEncodedBasicPayByMonth($employee,$payroll_periods );

            $monthly_arr[$key] = $result->basic_pay + $manual_input;
        }

        return $monthly_arr;
       
    }

    function employeeJLRFactory($employee,$year,$months)
    {
        //basicPayQueryJLR

        $basic_pays = $this->basicPayQueryJLR($employee,$year,$months);
        $manual_input = $this->getEncodedBasicPay($employee,$year,$months);
        if(is_array($months)){
            $monthly = $this->buildMonthlyPay($employee,$year,$months);

            $month = (count($months) == 7) ? 2 : 1;
        }else{
            $monthly = [];
            $month = $months;
        }

        return new ThirteenthMonthJLREmployee($month,$employee,$basic_pays,$manual_input,$monthly);
    }

    function baseQuery()
    {
        $result = DB::table("payrollregister_posted_weekly")
            ->leftJoin('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
            ->leftJoin('payroll_period_weekly','payroll_period_weekly.id','=','payrollregister_posted_weekly.period_id')
            ->leftJoin('locations','employees.location_id','=','locations.id')
            ->where('employees.exit_status',1);

        return $result;
    }

    function baseQueryI()
    {
        $result = DB::table("payrollregister_posted_weekly")
            ->leftJoin('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
            ->leftJoin('payroll_period_weekly','payroll_period_weekly.id','=','payrollregister_posted_weekly.period_id')
            ->leftJoin('locations','employees.location_id','=','locations.id')
            ->where('employees.exit_status','!=',1);

        return $result;
    }

    function baseQueryW()
    {
        $result = DB::connection('weekly')->table("payrollregister_posted_weekly")
            ->leftJoin('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
            ->leftJoin('payroll_period_weekly','payroll_period_weekly.id','=','payrollregister_posted_weekly.period_id')
            ->leftJoin('locations','employees.location_id','=','locations.id')
            ->where('employees.exit_status',1);

        return $result;
    }

    function basicPayQuery($employee,$year)
    {   
        $subQuery = DB::table('payrollregister_posted_weekly')
                ->select(DB::raw("payrollregister_posted_weekly.period_id,payrollregister_posted_weekly.biometric_id,basic_pay,basic_salary,ndays,late_eq_amount,ifnull(posted_weekly_compensation.retro_pay,0.00) retro_pay"))
                ->leftJoin('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
                ->leftJoin('posted_weekly_compensation',function($join){
                    $join->on('payrollregister_posted_weekly.period_id','=','posted_weekly_compensation.period_id');
                    $join->on('payrollregister_posted_weekly.biometric_id','=','posted_weekly_compensation.biometric_id');
                })
                ->where('payroll_period_weekly.pyear','=',$year)
                ->where('payrollregister_posted_weekly.biometric_id','=',$employee->biometric_id);
        
        
        // dd($employee->biometric_id);
        $result = DB::table("payroll_period_weekly")
            ->select(DB::raw("payroll_period_weekly.id,ifnull(subQuery.basic_pay,0.00) as basic_pay,late_eq_amount,ifnull(retro_pay,0.00) retro_pay"))
            ->leftJoinSub($subQuery,'subQuery',function($join){
                $join->on('payroll_period_weekly.id','=','subQuery.period_id');
            })
            ->where('payroll_period_weekly.pyear',$year)->orderBy('payroll_period_weekly.date_from','ASC')
            ->get();

        return $result;
    }

    public function getPayrollPeriodsJLR($year,$months)
    {
        if($months == 1){
            $m = [12,1,2,3,4];
        }else{
            $m = [5,6,7,8,9,10,11];
        }

        $payroll_periods = DB::table('payroll_period')
            ->select(DB::raw("payroll_period.*,concat(DATE_FORMAT(date_from,'%b'),' ',DATE_FORMAT(date_from,'%d'),' - ',DATE_FORMAT(date_to,'%d')) as label"))
            ->whereIn(DB::raw('month(date_from)'),$m)->where('pyear',$year);

        return  $payroll_periods;

    }

    public function getPayrollPeriodsByMonth($year,$month)
    {

        return DB::table('payroll_period') ->where('payroll_period.pyear','=',$year)
            ->whereRaw('month(date_from) = ?',[$month])
            ->get();
    }

    public function basicPayQueryJLR($employee,$year,$months)
    {
        $payroll_periods = $this->getPayrollPeriodsJLR($year,$months);

        $copmensations = DB::table('posted_other_compensations')
        ->whereIn('period_id',$payroll_periods->pluck('id'))
        ->where('biometric_id',$employee->biometric_id);
        
        $payrolls = DB::table('payrollregister_posted_s')
                ->whereIn('period_id',$payroll_periods->pluck('id'))
                ->where('biometric_id',$employee->biometric_id);

        $result = DB::table('payroll_period')
            ->leftJoinSub($payrolls,'subQuery',function($join){ 
                $join->on('payroll_period.id','=','subQuery.period_id');
            })
             ->leftJoinSub($copmensations,'subQuery2',function($join){ 
                $join->on('payroll_period.id','=','subQuery2.period_id');
            })


            // ->select(DB::raw("payroll_period.id,ifnull(subQuery.basic_pay,0.00) as basic_pay,late_eq_amount"))
            ->select(DB::raw("payroll_period.id,ifnull(subQuery.basic_pay,0.00) 
                    + ifnull(sl_wpay_amount,0.00) 
                    + ifnull(bl_wpay_amount,0.00) 
                    + ifnull(vl_wpay_amount,0.00)
                    + ifnull(svl_amount,0.00)
                    + ifnull(semi_monthly_allowance,0.00)
                    + ifnull(leghol_count_amount,0.00)
                    + ifnull(sphol_count_amount,0.00)
                    + ifnull(subQuery2.amount,0.00)
                    as basic_pay"))
            ->whereIn('id',$payroll_periods->pluck('id'));

        return $result->get();

        
    }

    function insertOrUpdate($key, $value)
    {
        DB::table('payrollregister_posted_weekly')
        ->updateOrInsert(
            ['biometric_id' => $key[0], 'period_id' => $key[1]],
            ['basic_pay' => $value]
        );
    }


    public function post13thMonth($year)
    {
        $locations = $this->baseQuery()->select(DB::raw("employees.location_id,location_name"))
        ->distinct()
        ->where('payroll_period_weekly.pyear',$year)
        ->orderBy('location_id','ASC')
        ->get();

        // DB::table('thirteenth_month_sg')->where('pyear' ,$year)->where('user_id',Auth::user()->id)->delete();

        foreach($locations as $location)
        {
            $employee_array = [];

            $employees = $this->baseQuery()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();

            foreach($employees as $employee)
            {
                // array_push($employee_array,)
                
                $e = $this->employeeFactory($employee,$year);

                if($e){
                    // DB::table('thirteenth_month_sg')->updateOrInsert([
                    //         'pyear' => $year,
                    //         'biometric_id' => $e->getBiometricID() 
                    //     ],[
                    //         'net_pay' => $e->getNetPay()
                    //     ]);

                    DB::table('thirteenth_month_sg')->insert(['user_id' => Auth::user()->id,
                    'pyear' => $year, 
                    'biometric_id' => $e->getBiometricID(),
                    'net_pay' => $e->getNetPay(),
                    'stat' => 'POSTED',
                    'gross_pay' => $e->getGrossPay(),
                    'created_on' => now() ]);
                }

                

                array_push($employee_array,$e);
            }

            $location->employees = $employee_array;
        
        }
    }

    public function getPosted($cyear)
    {
       
        $result = DB::table('thirteenth_month_sg')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,thirteenth_month_sg.net_pay,thirteenth_month_sg.net_pay'))
        ->leftJoin('employees','thirteenth_month_sg.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','thirteenth_month_sg.biometric_id','=','employee_names_vw.biometric_id')
        ->where('pyear','=',$cyear)
        ->where('stat','POSTED')
        ->orderBy('lastname','ASC')
        ->orderBy('firstname','ASC');

        return $result->get();
    }

    public function getPostedJLR($year,$month,$emp_level)
    {
       
        $result = DB::table('thirteenth_month')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,thirteenth_month.net_pay'))
        ->leftJoin('employees','thirteenth_month.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','thirteenth_month.biometric_id','=','employee_names_vw.biometric_id')
        ->where('pyear','=',$year)
        ->where('semi_annual','=',$month)
        ->where('stat','POSTED')
        ->orderBy('lastname','ASC')
        ->orderBy('firstname','ASC');

        if($emp_level == 'confi')
        {
            $result->where('employees.emp_level','<','5');
        }else{
            $result->where('employees.emp_level','=','5');
        }

        return $result->get();
    }

    public function getConsoPosted($cyear)
    {
       
        $semi = DB::table('thirteenth_month_sg')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,thirteenth_month_sg.net_pay,thirteenth_month_sg.net_pay'))
        ->leftJoin('employees','thirteenth_month_sg.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','thirteenth_month_sg.biometric_id','=','employee_names_vw.biometric_id')
        ->where('pyear','=',$cyear)
        ->where('employees.exit_status',1)
        ->where('stat','POSTED')->get();
        // ->orderBy('lastname','ASC')
        // ->orderBy('firstname','ASC');
    
        $weekly = DB::connection('weekly')->table('thirteenth_month_sg')->select(DB::raw('employee_names_vw.employee_name,employees.bank_acct,thirteenth_month_sg.net_pay,thirteenth_month_sg.net_pay'))
        ->leftJoin('employees','thirteenth_month_sg.biometric_id','=','employees.biometric_id')
        ->leftJoin('employee_names_vw','thirteenth_month_sg.biometric_id','=','employee_names_vw.biometric_id')
        ->where('pyear','=',$cyear)
        ->where('stat','POSTED')
        ->where('employees.exit_status',1)
        ->orderBy('employee_name','ASC')
        
        ->get();

        $merged = $weekly->merge($semi);

        // dd($merged);

        return $merged;

        // return $weekly->get();
    }

    public function isPosted($year)
    {
  
        $result = DB::table("thirteenth_month_sg")
                ->where('stat','=','POSTED')
                ->where('pyear','=',$year)
                ->get();

        return (count($result)>0) ? true : false;
    }

    public function getNetpay($year,$location)
    {
        $result = DB::table('thirteenth_month_sg')
        ->leftJoin('employees','employees.biometric_id','=','thirteenth_month_sg.biometric_id')
        ->leftJoin('employee_names_vw','employee_names_vw.biometric_id','=','thirteenth_month_sg.biometric_id')
        ->select(DB::raw('thirteenth_month_sg.net_pay,thirteenth_month_sg.biometric_id,employee_names_vw.employee_name'))
        ->where('stat','POSTED')->where('pyear',$year);

        if($location!=0)
        {
            $result->where('location_id','=',$location);
        }

        return $result->get();
    }

    public function getNetpayJLR($year,$month,$emp_level)
    {
     
        $result = DB::table('thirteenth_month')
            ->leftJoin('employees','employees.biometric_id','=','thirteenth_month.biometric_id')
            ->leftJoin('employee_names_vw','employee_names_vw.biometric_id','=','thirteenth_month.biometric_id')
            ->select(DB::raw('thirteenth_month.net_pay,thirteenth_month.biometric_id,employee_names_vw.employee_name'))
            ->where('stat','POSTED')
            ->where('semi_annual',$month)
            ->where('thirteenth_month.emp_level',$emp_level)
            ->where('pyear',$year);

        return $result->get();
    }

    public function getLocation($id)
    {
        if($id == 0)
        {
            return "All locations";
        }else {
            $location = DB::table('locations')->where('id',$id)->first();
            return $location->location_name;
        }
    }

    public function getRange($year)
    {
        $start = DB::table('payroll_period_weekly')->select(DB::raw("date_format(date_from,'%M %Y') as label"))
                ->where('pyear',$year)
                ->orderBy('id','ASC')
                ->first();

        $end = DB::table('payroll_period_weekly')->select(DB::raw("date_format(date_from,'%M %Y') as label"))
                ->where('pyear',$year)
                ->orderBy('id','DESC')
                ->first();

        return $start->label . ' - ' . $end->label;
    }

    public function buildSemiMonthlyJLR($months,$month,$year)
    {
        $employees = $this->getConfi()->get();

        foreach($employees as $employee)
        {
            $e = $this->employeeJLRFactory($employee,$year,$months);

            // if($e){
            //     // dd($e->getMonthly());
            // }

            $employee->thirteenth_month_monthly = $e;
        }

        // return $employees;
        return [
            'employees' => $employees,
            'payroll_periods' => $this->getPayrollPeriodsJLR($year,$months)->get(),
        ];
    }

    public function buildSemiMonthly($months,$year)
    {
        // $employee
        $locations = $this->baseQuery()->select(DB::raw("employees.location_id,location_name"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->orderBy('location_id','ASC')
                ->get();

        foreach($locations as $location)
        {
            $employees = $this->baseQuery()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();
            
                foreach($employees as $e)
                {
                    $basic_pays = [];

                    foreach($months as $key => $value)
                    {
                        $result = DB::table('payrollregister_posted_weekly')
                        ->join('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
                        ->select(DB::raw("ifnull(sum(basic_pay),0.00) as basic_pay"))
                        ->where('payrollregister_posted_weekly.biometric_id','=',$e->biometric_id)
                        ->where('payroll_period_weekly.pyear','=',$year)
                        ->whereRaw('month(date_from) = ?',[$key])->first();
                        // $basic_pay[$key]

                        $basic_pays[$key] = $result->basic_pay;
                    }

                    $e->basic = $basic_pays;
                }
            $location->employees = $employees;
        }

        return $locations;
    }

    public function buildWeekly($months,$year)
    {
        // $employee
        $locations = $this->baseQueryW()->select(DB::raw("employees.location_id,location_name"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->orderBy('location_id','ASC')
                ->get();

        foreach($locations as $location)
        {
            $employees = $this->baseQueryW()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();
            
                foreach($employees as $e)
                {
                    $basic_pays = [];

                    foreach($months as $key => $value)
                    {
                        $result = DB::connection('weekly')->table('payrollregister_posted_weekly')
                        ->join('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
                        ->select(DB::raw("ifnull(sum(basic_pay),0.00) as basic_pay"))
                        ->where('payrollregister_posted_weekly.biometric_id','=',$e->biometric_id)
                        ->where('payroll_period_weekly.pyear','=',$year)
                        ->whereRaw('month(date_from) = ?',[$key])->first();
                        // $basic_pay[$key]

                        $basic_pays[$key] = $result->basic_pay;
                    }

                    $e->basic = $basic_pays;
                }
            $location->employees = $employees;
        }

        return $locations;


    }

    public function insertOrUpdateJLR($key, $value)
    {
       
        $user = Auth::user();
      
        return DB::table('manual_basicpay')
            ->updateOrInsert(
                [
                    'period_id' => $key[1], 
                    'biometric_id' => $key[0],
                ],
                [
                    'basic_pay' => $value,
                    'updated_on' => now(),
                    'updated_by' => $user->id
                ]
            );
    }

    public function isPostedJLR($year,$month,$emp_level)
    {
        $result = DB::table("thirteenth_month")
                ->where('stat','=','POSTED')
                ->where('pyear','=',$year)
                ->where('semi_annual','=',$month)
                ->where('emp_level','=',$emp_level)
                ->get();

        return (count($result)>0) ? true : false;
    }

    public function post13thMonthJLR($year,$month,$emp_level)
    {
        $user = Auth::user();

        $result = DB::table("thirteenth_month")
                ->where('stat','=','DRAFT')
                ->where('pyear','=',$year)
                ->where('semi_annual','=',$month)
                ->where('emp_level','=',$emp_level)
                ->where('user_id',$user->id)
                ->get();
        
        foreach($result as $row){
            $result = DB::table('thirteenth_month')->insert([
                'user_id' => $user->id,
                'pyear' => $year,
                'semi_annual' => $month,
                'biometric_id' => $row->biometric_id,
                'net_pay' => $row->net_pay,
                'gross_pay' => $row->gross_pay,
                'created_on' => now(),
                'emp_level' => $row->emp_level,
                'stat' => 'POSTED',
            ]);

        }
        
    }


}
/*

period_id
emp_level
updated_on
updated_by
basic_pay

select ifnull(sum(basic_pay),0.00) as basic_pay from payrollregister_posted_weekly 
inner join payroll_period_weekly on payrollregister_posted_weekly.period_id = payroll_period_weekly.id
where payroll_period_weekly.pyear = 2024
and biometric_id = 10
and month(date_from) = 11;
*/
