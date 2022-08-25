<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\Employee';
    protected $rules = [
    	'firstname' => 'required|sometimes',
		'lastname' => 'required|sometimes',
		'middlename' => 'required|sometimes',
		//'suffixname' => 'required|sometimes',
		'biometric_id' => 'required|sometimes|unique:employees',
		// primary_addr
		// secondary_addr
		// remarks
		// sss_no
		// deduct_sss
		// tin_no
		// phic_no
		// deduct_phic
		// hdmf_no
		// deduct_hdmf
		// hdmf_contri
		// civil_status
		// gender
		// birthdate
		// employee_stat
		// bank_acct
		// basic_salary
		// is_daily
		// exit_status
    ];

	public function header($id){
		return $this->model->find($id);
    }

    public function list($filter)
    {
        $result = $this->model->select(DB::raw('employees.*,dept_code,div_code'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])
		->orderBy('lastname','ASC')
		->orderBy('firstname','ASC')
		->orderBy('middlename','ASC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

	public function generateReport($filter)
	{
		$result = $this->model->select(DB::raw('employees.*,dept_code,div_code'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('civil_status','employees.civil_status','=','civil_status.id');

		if($filter['division']!=0){
			$result = $result->where('division_id',$filter['division']);
		}

		return $result->get();
	}


}
