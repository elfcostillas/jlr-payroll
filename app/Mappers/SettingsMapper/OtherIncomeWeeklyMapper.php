<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtherIncomeWeeklyMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\OtherIncomeWeekly';
    protected $rules = [
        // 'loan_code' => 'required|sometimes|unique:loan_types',
        // 'description' => 'required|sometimes|unique:loan_types',
        // 'sched' => 'required|sometimes',

    ];

    public function list($filter)
    {
        //SELECT id,description FROM deduction_types
        $result = $this->model->select('id','description','amount')->from('compensation_other_weekly');

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

}
