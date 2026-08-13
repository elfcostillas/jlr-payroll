<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class ManHoursMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
      
    ];

    protected $db_table = 'payrollregister_posted_s';


    public function mainQuery()
    {

        $result = DB::table('employees')->where('employees.job_title_id', '!=',130);

        $result->join($this->db_table,'employees.biometric_id','=',$this->db_table.'.biometric_id');

        return $result;
    }

    public function getData($from,$to,$filtered,$h1,$h2){
        $qry2 = "select edtr.biometric_id,employee_names_vw.employee_name,sum(ndays* 8) as hrs ,sum(over_time) ot,sum(ndays* 8)  + sum(over_time) as total 
        from edtr 
        inner join employee_names_vw on employee_names_vw.biometric_id = edtr.biometric_id
        where dtr_date between '".$from."' and '".$to."'
        group by edtr.biometric_id,employee_names_vw.employee_name
        having (sum(ndays* 8)  + sum(over_time) ) > 72
        ORDER BY employee_name;";
        
        if($h1 != $h2){
            $filter = "and total between $h1 and $h2";

            if($h1 == 0 || $h2 ==0){
                //$h3 = $h1 + $h2;
                
                $filter = "and total >= ".($h1+$h2);
            }
         
        }else{
            $filter = ($h1 >0 ) ? 'and total = '.$h1 : '';
        }

        // $filter = ($filtered=='Y') ? 'AND (sum(ndays* 8)  + sum(over_time) ) > 72' : '';

        $qry = "    select edtr.biometric_id,employee_names_vw.employee_name,sum(ndays* 8) as hrs ,sum(over_time) ot,sum(ndays* 8)  + sum(over_time) as total,divisions.div_code,departments.dept_code,job_titles.job_title_name
        from edtr 
        inner join employee_names_vw on employee_names_vw.biometric_id = edtr.biometric_id
        inner join employees on employee_names_vw.biometric_id = employees.biometric_id
        left join departments on employees.dept_id = departments.id
        left join divisions on employees.division_id = divisions.id
        left join job_titles on employees.job_title_id = job_titles.id
        where dtr_date between '".$from."' and '".$to."'
        group by edtr.biometric_id,employee_names_vw.employee_name,div_code,dept_code,job_title_name
        having total > 0 ".$filter."

        ORDER BY employee_name;";

        $result = DB::select($qry);

        return $result;
    }

    public function getDataOT($from,$to,$filtered,$h1,$h2){
      
        
        if($h1 != $h2){
            $filter = "and ot between $h1 and $h2";
        }else{
            $filter = ($h1 >0 ) ? 'and ot = '.$h1 : '';
        }

        // $filter = ($filtered=='Y') ? 'AND (sum(ndays* 8)  + sum(over_time) ) > 72' : '';

        $qry = "    select edtr.biometric_id,employee_names_vw.employee_name,sum(ndays* 8) as hrs ,sum(over_time) ot,divisions.div_code,departments.dept_code,job_titles.job_title_name
        from edtr 
        inner join employee_names_vw on employee_names_vw.biometric_id = edtr.biometric_id
        inner join employees on employee_names_vw.biometric_id = employees.biometric_id
        left join departments on employees.dept_id = departments.id
        left join divisions on employees.division_id = divisions.id
        left join job_titles on employees.job_title_id = job_titles.id
        where dtr_date between '".$from."' and '".$to."'
        group by edtr.biometric_id,employee_names_vw.employee_name,div_code,dept_code,job_title_name
        having ot > 0 ".$filter."

        ORDER BY employee_name;";

        $result = DB::select($qry);

        return $result;
    }

    public function getDataJLR($month,$year)
    {
        // dd($month,$year);

        $periods = DB::table('payroll_period')->select('id')
            ->whereRaw("MONTH(date_from) = ? ",[$month])
            ->whereRaw("YEAR(date_from) = ? ",[$year])
            ->pluck('id');

        $data = $this->getCountsByDiviosionDept($periods);

        return $data;

    }

    public function getCountsByDiviosionDept($periods)
    {
      
        $divisions = $this->mainQuery()
                ->leftJoin('divisions','divisions.id','=','employees.division_id')
                ->select('divisions.id','div_code')
                ->orderBy('divisions.id','ASC')
                ->groupBy('divisions.id')
                ->get();
            
            foreach($divisions as $division)
            {
                $departments = $this->mainQuery()
                    ->join('departments','employees.dept_id','=','departments.id')
                    ->where('dept_div_id',$division->id)
                    ->select(DB::raw("dept_code,

                        SUM(CASE
                            WHEN period_id = ".$periods[0]." AND  payrollregister_posted_s.emp_level = 'confi'
                            THEN (ndays * 8) + reg_ot + rd_hrs + rd_ot + leghol_hrs + sphol_hrs_amount
                            ELSE 0
                        END) AS man_hours_1sthalf_confi,

                        SUM(CASE
                            WHEN period_id = ".$periods[0]." AND  payrollregister_posted_s.emp_level = 'non-confi'
                            THEN (ndays * 8) + reg_ot + rd_hrs + rd_ot + leghol_hrs + sphol_hrs_amount
                            ELSE 0
                        END) AS man_hours_1sthalf_rnf,

                       SUM(
                            CASE
                                WHEN payrollregister_posted_s.period_id = ".$periods[0]."
                                AND employees.gender = 'M'
                                THEN 1
                                ELSE 0
                            END
                        ) AS male_1sthalf,

                        SUM(
                            CASE
                                WHEN payrollregister_posted_s.period_id = ".$periods[0]."
                                AND employees.gender = 'F'
                                THEN 1
                                ELSE 0
                            END
                        ) AS female_1sthalf,
                         
                        SUM(CASE
                            WHEN period_id = ".$periods[1]." AND  payrollregister_posted_s.emp_level = 'confi'
                            THEN ndays * 8
                            ELSE 0
                        END) AS man_hours_2ndthalf_confi,

                        SUM(CASE
                            WHEN period_id = ".$periods[1]." AND  payrollregister_posted_s.emp_level = 'non-confi'
                            THEN ndays * 8
                            ELSE 0
                        END) AS man_hours_2ndhalf_rnf,

                       SUM(
                            CASE
                                WHEN payrollregister_posted_s.period_id = ".$periods[1]."
                                AND employees.gender = 'M'
                                THEN 1
                                ELSE 0
                            END
                        ) AS male_2ndhalf,

                        SUM(
                            CASE
                                WHEN payrollregister_posted_s.period_id = ".$periods[1]."
                                AND employees.gender = 'F'
                                THEN 1
                                ELSE 0
                            END
                        ) AS female_2ndhalf
                    "))
                    ->whereIn($this->db_table.'.period_id',$periods->all())
                    ->groupBy('dept_code')
                    ->get();


                $division->departments = $departments;
            }
        
        return $divisions;
    }

}

?>