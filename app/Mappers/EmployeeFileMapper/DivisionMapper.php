<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DivisionMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\Division';
    protected $rules = [
    	'div_code' => 'required|sometimes|unique:divisions',
		'div_name' => 'required|sometimes|unique:divisions'
    ];

    public function list($filter)
    {
        $result = $this->model->select('id','div_code','div_name');

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

	public function getDivisions()
	{
		$result = $this->model->select('id','div_code','div_name');

		return $result->get();
	}



}
