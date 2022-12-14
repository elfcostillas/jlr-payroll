<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DefaultScheduleMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\DefaultSchedule';
    protected $rules = [
    	
    ];

    public function list($filter)
    {
        $result = $this->model->select(DB::raw("work_schedules_default.line_id,departments.id as dept_id,CONCAT(div_code,' - ',dept_code) AS dept_code,ifnull(work_schedules_default.schedule_id,0) schedule_id,concat(work_schedules.time_in,'-',work_schedules.time_out) as sched_desc,schedule_sat,concat(sat_schedules.time_in,'-',sat_schedules.time_out) as sched_sat_desc"))->from('departments')
        ->join('divisions','dept_div_id','=','divisions.id')
        ->leftJoin('work_schedules_default','departments.id','=','work_schedules_default.dept_id')
        ->leftJoin('work_schedules','work_schedules.id','=','work_schedules_default.schedule_id')
		->leftJoin('work_schedules as sat_schedules','sat_schedules.id','=','work_schedules_default.schedule_sat');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('div_code')->orderBy('dept_code');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

	public function getLocations()
	{
		$result = $this->model->select('id','location_name');
		return $result->get();
	}

    public function updateOrCreate($data)
    {
        $result = $this->model->updateOrCreate($data,$data);
    }



}
