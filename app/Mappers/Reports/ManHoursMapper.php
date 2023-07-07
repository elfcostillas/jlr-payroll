<?php

namespace App\Mappers\Reports;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManHoursMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\DailyTimeRecord';
    protected $rules = [
      
    ];

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

}

?>