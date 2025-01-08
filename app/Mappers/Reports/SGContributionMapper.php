<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use Illuminate\Support\Facades\DB;

class SGContributionMapper extends AbstractMapper 
{
    //
    protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
    	
    ];

    public function yearList()
    {   
        //SELECT DISTINCT YEAR(date_from) AS fy FROM payroll_period 
        $result = $this->model->select(DB::raw("DISTINCT YEAR(date_from) AS fy"))->from('payroll_period')
                    ->orderBy("fy","asc");
        return $result->get();            

    }

    public function generate($year, $month)
    {
        $locations = DB::table('employees')->select(DB::raw("distinct locations.*"))
        ->join('payrollregister_posted_weekly','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
        ->join('locations','employees.location_id','=','locations.id')
        ->whereRaw("payrollregister_posted_weekly.period_id in (select id from payroll_period_weekly where month(date_from) = ".$month." and year(date_from) = ".$year.")")
        ->orderBy('employees.location_id','asc')
        ->get();

        $contri = DB::table("payrollregister_posted_weekly")
        ->select(DB::raw("biometric_id,sum(ifnull(sss_prem,0)) as sss_prem,sum(ifnull(phil_prem,0)) as phil_prem,sum(ifnull(payrollregister_posted_weekly.hdmf_contri,0)) as hdmf_contri,er_share,ec"))
        ->join('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
        ->leftJoin('hris_sss_table','ee_share','=','payrollregister_posted_weekly.sss_prem')
        ->whereRaw("month(date_from) = $month and year(date_from) = $year")
        ->groupBy('biometric_id');

        foreach($locations as $location)
        {
            $employees = DB::table('payrollregister_posted_weekly')
            ->select(DB::raw("payrollregister_posted_weekly.biometric_id,departments.dept_code,job_titles.job_title_name, concat(
                    ifnull(`employees`.`lastname`, ''),
                    ', ',
                    ifnull(`employees`.`firstname`, ''),
                    ' ',
                    ifnull(`employees`.`suffixname`, ''),
                    ' ',
                    ifnull(`employees`.`middlename`, '')
                ) AS `employee_name`,
                employees.lastname,employees.firstname,employees.middlename,contri.sss_prem,contri.phil_prem,contri.hdmf_contri,er_share,ec,tin_no,phic_no,sss_no"))
            ->join('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
            ->joinSub($contri,'contri','contri.biometric_id','=','employees.biometric_id')
            
            ->join('departments','departments.id','=','employees.dept_id')
            ->join('job_titles','job_titles.id','=','employees.job_title_id')
            ->where('employees.location_id',$location->id)
            
            ->groupBy('biometric_id')
            ->get();

            $location->employees = $employees;
        }

        return $locations;
    
    }
}

/*

select biometric_id,sum(ifnull(sss_prem,0)) as sss_prem,sum(ifnull(phil_prem,0)) as phil_prem,sum(ifnull(payrollregister_posted_weekly.hdmf_contri,0)) as hdmf_contri 
from payrollregister_posted_weekly 
inner join payroll_period_weekly on payrollregister_posted_weekly.period_id = payroll_period_weekly.id
where month(date_from) = 11 and year(date_from) = 2024
group by biometric_id


select distinct locations.* from employees 
inner join payrollregister_posted_weekly on payrollregister_posted_weekly.biometric_id = employees.biometric_id
inner join locations on employees.location_id = locations.id
where payrollregister_posted_weekly.period_id in (select id from payroll_period where month(date_from) = 11 and year(date_from) = 2024)
order by employees.location_id asc;

select payrollregister_posted_weekly.biometric_id,departments.dept_code,job_titles.job_title_name,employees.lastname,employees.firstname,employees.middlename,sum(ifnull(sss_prem,0)) as sss_prem,sum(ifnull(phil_prem,0)) as phil_prem,sum(ifnull(payrollregister_posted_weekly.hdmf_contri,0)) as hdmf_cntri 
from payrollregister_posted_weekly 
inner join employees on payrollregister_posted_weekly.biometric_id = employees.biometric_id
inner join departments on departments.id = employees.dept_id
inner join job_titles on job_titles.id = employees.job_title_id
group by biometric_id;


*/