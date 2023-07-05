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

    public function getData($from,$to){
        $qry = "select edtr.biometric_id,employee_names_vw.employee_name,sum(ndays* 8) as hrs ,sum(over_time) ot,sum(ndays* 8)  + sum(over_time) as total 
        from edtr 
        inner join employee_names_vw on employee_names_vw.biometric_id = edtr.biometric_id
        where dtr_date between '".$from."' and '".$to."'
        group by edtr.biometric_id,employee_names_vw.employee_name
        having (sum(ndays* 8)  + sum(over_time) ) > 72
        ORDER BY employee_name;";

        $result = DB::select($qry);

        return $result;
    }

}

?>