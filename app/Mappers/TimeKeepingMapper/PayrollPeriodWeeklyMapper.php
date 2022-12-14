<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollPeriodWeeklyMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriodWeekly';
    protected $rules = [
    	
    ];

    public function list($filter)
    {
        $result = $this->model->select('id','date_from','date_to','date_release','man_hours');

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


}
