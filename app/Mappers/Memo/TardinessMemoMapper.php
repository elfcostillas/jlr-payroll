<?php

namespace App\Mappers\Memo;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TardinessMemoMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Memo\TardinessMemo';
    protected $rules = [
        

    ];

    public function find($id){
        $result = $this->model->find($id);
        return $result;
    }

    public function list($filter)
    {
        //SELECT id,description FROM deduction_types
        $result = $this->model->select('id','biometric_id','memo_to','memo_from','memo_date','memo_subject')->from('tardiness_memo');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','desc');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

    public function readMemo($id)
    {
        if($id==0){
            $result = $this->model->select(DB::raw('0 as id'),
            'biometric_id',
            'memo_to',
            'memo_from',
            'memo_date',
            'memo_subject',
            'memo_upper_body',
            'memo_lower_body',
            'prep_by_text',
            'prep_by_name',
            'prep_by_position',
            'noted_by_text',
            'noted_by_name',
            'noted_by_position',
            'noted_by_text_dept',
            'noted_by_name_dept',
            'noted_by_position_dept',
            'memo_month',
            'memo_year'
            )
            ->from('tardiness_memo_template')
            ->where('id',1);

        }else{
            $result = $this->model->select('id',  'biometric_id',
            'memo_to',
            'memo_from',
            'memo_date',
            'memo_subject',
            'memo_upper_body',
            'memo_lower_body',
            'prep_by_text',
            'prep_by_name',
            'prep_by_position',
            'noted_by_text',
            'noted_by_name',
            'noted_by_position',
            'noted_by_text_dept',
            'noted_by_name_dept',
            'noted_by_position_dept',
            'memo_month',
            'memo_year')
            ->from('tardiness_memo')
            ->where('id',$id);
        }

        return $result->first();
    }

    public function getNames()
    {
        $qry = "SELECT biometric_id,CONCAT(TRIM(lastname),', ',TRIM(firstname),' ',CASE 
        WHEN middlename IS NULL THEN ''
        WHEN middlename = '' THEN ''
        ELSE CONCAT(SUBSTR(middlename,1,1),'. ') 
        END,IFNULL(suffixname,'') ) AS employee_name
        FROM employees WHERE exit_status = 1
        ORDER BY lastname,firstname";

        $result = DB::select($qry);

        return $result;
    }

    public function getYear()
    {
        //SELECT DISTINCT YEAR(dtr_date) AS dtr_year FROM edtr;
        $result = $this->model->select(DB::raw("YEAR(dtr_date) as dtr_year"))
                    ->from('edtr')
                    ->orderBy('dtr_year','desc')
                    ->distinct();
        return $result->get();
    }

    public function getLates($biometric_id,$filter)
    {
        $holidays = DB::table('holidays')->join('holiday_location','holidays.id','=','holiday_location.holiday_id')
        ->select(DB::raw("holiday_date,location_id,holiday_type"));

        $lates = $this->model->select(DB::raw("dtr_date, edtr.time_in,edtr.time_out,ROUND((TIME_TO_SEC(edtr.time_in) - TIME_TO_SEC(work_schedules.time_in))/60,0) AS in_minutes"))
        ->from('edtr')
        ->join('employees','edtr.biometric_id','=','employees.biometric_id')
        ->leftJoin('work_schedules','schedule_id','=','work_schedules.id')
        ->leftJoinSub($holidays,'holidays',function($join){
            $join->on('holidays.location_id','=','employees.location_id');
            $join->on('edtr.dtr_date','=','holidays.holiday_date');
        })
        // ->whereNull('leave_type')
        ->whereNull('holiday_type')
        ->whereBetween('dtr_date',[$filter['from'],$filter['to']])
        ///->whereRaw('TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in)')
        ->whereRaw('(
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.time_in) && TIME_TO_SEC(edtr.time_in) < TIME_TO_SEC(work_schedules.out_am)-900) OR
            (TIME_TO_SEC(edtr.time_in) > TIME_TO_SEC(work_schedules.in_pm) && TIME_TO_SEC(work_schedules.time_in) < TIME_TO_SEC(work_schedules.time_out) )
            )')
        ->where('edtr.biometric_id',$biometric_id)
        ->get();

        return $lates;
    }

    public function getManualTardy($biometric_id)
    {
        $result = $this->model->select('tardy_count')->from('manual_tardy')->where('biometric_id',$biometric_id);

        return $result->first();
    }

}
