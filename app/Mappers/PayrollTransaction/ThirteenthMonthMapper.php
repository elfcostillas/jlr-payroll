<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Models\PayrollTransaction\ThirteenthMonthEmployee;
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

        $result = DB::table("payroll_year")
            ->select(DB::raw("pyear as text,pyear as value"))->get();
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

    function employeeFactory($employee,$year)
    {
        $basic_pays = $this->basicPayQuery($employee,$year);
        return new ThirteenthMonthEmployee($employee,$basic_pays);
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
                ->select(DB::raw("period_id,biometric_id,basic_pay,basic_salary,ndays"))
                ->leftJoin('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
                ->where('payroll_period_weekly.pyear','=',$year)
                ->where('biometric_id','=',$employee->biometric_id);
        
        
        // dd($employee->biometric_id);
        $result = DB::table("payroll_period_weekly")
            ->select(DB::raw("payroll_period_weekly.id,ifnull(subQuery.basic_pay,0.00) as basic_pay"))
            ->leftJoinSub($subQuery,'subQuery',function($join){
                $join->on('payroll_period_weekly.id','=','subQuery.period_id');
            })
            ->where('payroll_period_weekly.pyear',$year)->orderBy('payroll_period_weekly.date_from','ASC')
            ->get();

        return $result;
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

    // public function 

    
}
/*

select ifnull(sum(basic_pay),0.00) as basic_pay from payrollregister_posted_weekly 
inner join payroll_period_weekly on payrollregister_posted_weekly.period_id = payroll_period_weekly.id
where payroll_period_weekly.pyear = 2024
and biometric_id = 10
and month(date_from) = 11;
*/
