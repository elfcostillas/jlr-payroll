<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobTitleMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\JobTitle';
    protected $rules = [
    	'dept_id' => 'required|sometimes|gt:0',
		'job_title_code' => 'required|sometimes|unique:job_titles|min:2|max:6',
		'job_title_name' => 'required|sometimes|unique:job_titles|min:4|max:32'
    ];

	protected $messages = [
		'dept_id.gt' => 'Please select a department.',
	];

    public function list($filter)
    {
		//SELECT id,dept_id,job_title_code,job_title_name,dept_code 
		//FROM job_titles INNER JOIN departments ON dept_id = departments.id; 
        $result = $this->model->select('job_titles.id','dept_id','job_title_code','job_title_name','dept_code')
								->join('departments','dept_id','=','departments.id');

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

	public function getDepartments()
	{
		$result = $this->model->select('id','dept_code')->from('departments');

		return $result->get();
	}


}