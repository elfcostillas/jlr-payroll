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
		'division_id' => 'required|sometimes',
		'dept_id' => 'required|sometimes|gt:0',
		'location_id' => 'required|sometimes|gt:0',
		'employee_stat' => 'required|sometimes|gt:0',
		'exit_status' => 'required|sometimes|gt:0',
		'pay_type' => 'required|sometimes|gt:0',
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
		// bank_acct
		// basic_salary
		// is_daily

    ];

	protected $messages = [
		'dept_id.gt' => 'Department field is required.',
		'location_id.gt' => 'Assigned Location field is required.',
		'employee_stat.gt' => 'Employment Status field is required.',
		'exit_status.gt' => 'Exit Status field is required.',
		'pay_type.gt' => 'Employee Pay Type Status field is required.'
	];

	public function header($id){
		return $this->model->find($id);
    }

    public function list($filter)
    {
        $result = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id');


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
		$result = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id');

		if($filter['division']!=0){
			$result = $result->where('division_id',$filter['division']);
		}

		return $result->get();
	}

	public function getEmploymentStat()
	{
		$result = $this->model->select('id','estatus_desc')->from('emp_emp_stat');
		return $result->get();
	}

	public function getExitStat()
	{
		$result = $this->model->select('id','status_desc')->from('emp_exit_status');
		return $result->get();
	}
	//SELECT id,pay_description FROM emp_pay_type;

	public function getPayTypes()
	{
		$result = $this->model->select('id','pay_description')->from('emp_pay_types');
		return $result->get();
	}
	




}
