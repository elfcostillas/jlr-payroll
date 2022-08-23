<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HolidayMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\Holiday';
    protected $rules = [
        'holiday_date' => 'required|sometimes|unique:holidays',
        'holiday_remarks' => 'required|sometimes',
        'holiday_type' => 'required|sometimes|gt:0',
    ];

    protected $messages = [
        'holiday_type.gt' => 'Please select holiday type'
    ];

    public function list($filter)
    {
        //$result = $this->model->select('id','date_from','date_to','date_release','man_hours');
        //SELECT `holidays`.id,`holiday_date`,`holiday_remarks`,`holiday_type`,`type_description` FROM holidays INNER JOIN holiday_types ON holidays.`holiday_type` = `holiday_types`.id

        $result = $this->model->select('holidays.id','holiday_date','holiday_remarks','holiday_type','type_description')
                            ->from('holidays')
                            ->join('holiday_types','holidays.holiday_type','=','holiday_types.id');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

    public function getHolidayTypes()
    {
        $result = $this->model->select('id','type_description')->from('holiday_types');

        return $result->get();
    }

    public function getLocations()
    {
        $result = $this->model->select('id','location_name')->from('locations');
        return $result->get();
    }

}
