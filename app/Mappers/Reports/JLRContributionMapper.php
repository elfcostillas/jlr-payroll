<?php

namespace App\Mappers\Reports;

use Illuminate\Support\Facades\DB;

class JLRContributionMapper
{
    //

    public function yearList()
    {   
        //SELECT DISTINCT YEAR(date_from) AS fy FROM payroll_period 
        // $result = $this->model->select(DB::raw("DISTINCT YEAR(date_from) AS fy"))->from('payroll_period')
        //             ->orderBy("fy","asc");
        // return $result->get();         
        
        $result = DB::table('payroll_period')
        ->select(DB::raw("DISTINCT YEAR(date_from) AS fy"))
        ;

        return $result->get();

    }

    public function generate($year, $month)
    {
        $locations = DB::table('employees')->select(DB::raw("distinct locations.*"))
        ->join('payrollregister_posted_s','payrollregister_posted_s.biometric_id','=','employees.biometric_id')
        ->join('locations','employees.location_id','=','locations.id')
        ->whereRaw("payrollregister_posted_s.period_id in (select id from payroll_period where month(date_from) = ".$month." and year(date_from) = ".$year.")")
        ->orderBy('employees.location_id','asc')
        ->get();

        // $contri = DB::table("payrollregister_posted_s")
        // ->select(DB::raw("biometric_id,sum(ifnull(sss_prem,0)) as sss_prem,sum(ifnull(phil_prem,0)) as phil_prem,sum(ifnull(payrollregister_posted_s.hdmf_contri,0)) as hdmf_contri,er_share,ec"))
        // ->join('payroll_period','payrollregister_posted_s.period_id','=','payroll_period.id')
        // ->leftJoin('hris_sss_table_2025','ee_share','=','payrollregister_posted_s.sss_prem')
        // ->whereRaw("month(date_from) = $month and year(date_from) = $year")
        // ->groupBy('biometric_id');

        // dd($contri->toSql(),$contri->getBindings());

        $contri = "SELECT * FROM (
            SELECT period_id,biometric_id,SUM(IFNULL(sss_prem,0)) AS sss_prem,SUM(IFNULL(phil_prem,0)) AS phil_prem,SUM(IFNULL(payrollregister_posted_s.hdmf_contri,0)) AS hdmf_contri
            FROM payrollregister_posted_s 
            INNER JOIN `payroll_period` ON `payrollregister_posted_s`.`period_id` = `payroll_period`.`id` 
            WHERE MONTH(date_from) = $month AND YEAR(date_from) = $year 
            GROUP BY `biometric_id`
            ) AS table1
            LEFT JOIN (SELECT DISTINCT ec,er_share,ee_share FROM hris_sss_table_2025) AS table2 ON `table2`.`ee_share` = table1.sss_prem";

        // $contri = DB::select($contri_qry);

      

        foreach($locations as $location)
        {
            $employees = DB::table('payrollregister_posted_s')
            ->select(DB::raw("payrollregister_posted_s.biometric_id,departments.dept_code,job_titles.job_title_name, concat(
                    ifnull(`employees`.`lastname`, ''),
                    ', ',
                    ifnull(`employees`.`firstname`, ''),
                    ' ',
                    ifnull(`employees`.`suffixname`, ''),
                    ' ',
                    ifnull(`employees`.`middlename`, '')
                ) AS `employee_name`,
                employees.lastname,employees.firstname,employees.middlename,contri.sss_prem,contri.phil_prem,contri.hdmf_contri,er_share,ec,tin_no,phic_no,sss_no,hdmf_no"))
            ->join('employees','payrollregister_posted_s.biometric_id','=','employees.biometric_id')
            ->joinSub($contri,'contri','contri.biometric_id','=','employees.biometric_id')
            
            ->join('departments','departments.id','=','employees.dept_id')
            ->join('job_titles','job_titles.id','=','employees.job_title_id')
            ->where('employees.location_id',$location->id)
            ->orderBy('lastname','asc')
            ->orderBy('firstname','asc')
            ->groupBy('biometric_id')
            ->get();

            $location->employees = $employees;
        }

        return $locations;
    
    }

    public function generateS($year, $month)
    {
       

        $contri = DB::table("payrollregister_posted_s")
        ->select(DB::raw("biometric_id,sum(ifnull(sss_prem,0)) as sss_prem,sum(ifnull(phil_prem,0)) as phil_prem,sum(ifnull(payrollregister_posted_s.hdmf_contri,0)) as hdmf_contri,er_share,ec"))
        ->join('payroll_period','payrollregister_posted_s.period_id','=','payroll_period.id')
        ->leftJoin('hris_sss_table_2025','ee_share','=','payrollregister_posted_s.sss_prem')
        ->whereRaw("month(date_from) = $month and year(date_from) = $year")
        ->groupBy('biometric_id');

        // foreach($locations as $location)
        // {
            $employees = DB::table('payrollregister_posted_s')
            ->select(DB::raw("payrollregister_posted_s.biometric_id,departments.dept_code,job_titles.job_title_name, concat(
                    ifnull(`employees`.`lastname`, ''),
                    ', ',
                    ifnull(`employees`.`firstname`, ''),
                    ' ',
                    ifnull(`employees`.`suffixname`, ''),
                    ' ',
                    ifnull(`employees`.`middlename`, '')
                ) AS `employee_name`,
                employees.lastname,employees.firstname,employees.middlename,contri.sss_prem,contri.phil_prem,contri.hdmf_contri,er_share,ec,tin_no,phic_no,sss_no,hdmf_no"))
            ->join('employees','payrollregister_posted_s.biometric_id','=','employees.biometric_id')
            ->joinSub($contri,'contri','contri.biometric_id','=','employees.biometric_id')
            
            ->join('departments','departments.id','=','employees.dept_id')
            ->join('job_titles','job_titles.id','=','employees.job_title_id')
            // ->where('employees.location_id',$location->id)
            ->orderBy('lastname','asc')
            ->orderBy('firstname','asc')
            ->groupBy('biometric_id')
            ->get();

        // }

        return $employees;
    
    }
}
