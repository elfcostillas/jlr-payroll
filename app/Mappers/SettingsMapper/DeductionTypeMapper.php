<?php

namespace App\Mappers\SettingsMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeductionTypeMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Settings\DeductionType';
    protected $rules = [
    	'description' => 'required|sometimes|unique:deduction_types',
        'is_fixed' => 'required|sometimes'

    ];

    public function list($filter)
    {
        //SELECT id,description FROM deduction_types
        $result = $this->model->select('id','description','is_fixed')->from('deduction_types');

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
