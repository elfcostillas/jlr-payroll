<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanTypeMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\LoanType';
    protected $rules = [
        'loan_code' => 'required|sometimes|unique:loan_types',
        'description' => 'required|sometimes|unique:loan_types',
        'sched' => 'required|sometimes',

    ];

    public function list($filter)
    {
        //SELECT id,description FROM deduction_types
        $result = $this->model->select('id','description','loan_code','sched')->from('loan_types');

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
