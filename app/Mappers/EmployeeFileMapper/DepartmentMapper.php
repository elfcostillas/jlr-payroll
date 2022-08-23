<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepartmentMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\Department';
    protected $rules = [
		'dept_div_id' => 'required|sometimes|gt:0',
        'dept_code' =>'required|sometimes|unique:departments',
        'dept_name' =>'required|sometimes|unique:departments',
    ];

	protected $messages = [
        'dept_div_id.gt' => 'Please select a division.'
    ];


    public function list($filter)
    {
		//SELECT div_code,departments.id,departments.dept_div_id,dept_code,dept_name FROM `divisions` 
		//INNER JOIN `departments` ON dept_div_id = divisions.id

        $result = $this->model->select('div_code','departments.id','departments.dept_div_id','dept_code','dept_name')
		->from('divisions')
		->join('departments','dept_div_id','=','divisions.id');

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
