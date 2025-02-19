<?php

namespace App\Mappers\EmployeeFileMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\EmployeeFile\Employee';
    protected $rules = [
    	'firstname' => 'required|sometimes',
		'lastname' => 'required|sometimes',
		//'middlename' => 'required|sometimes',
		//'suffixname' => 'required|sometimes',
		'biometric_id' => 'required|sometimes|unique:employees',
		//'bank_acct' => 'required|sometimes|unique:employees',

		// 'division_id' => 'required|sometimes',
		// 'dept_id' => 'required|sometimes|gt:0',
		// 'location_id' => 'required|sometimes|gt:0',
		//'employee_stat' => 'required|sometimes|gt:0',
		'exit_status' => 'required|sometimes|gt:0',
		// 'pay_type' => 'required|sometimes|gt:0',
		'job_title_id' => 'required|sometimes',
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

		$user = Auth::user();
		
		
        $result = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id')
		->where('pay_type','!=',3);

		// if($user->super_user=='N')
		// {
		// 	$result = $result->where('emp_level','>=',5);
		// }
		// else
		// {
		// 	$result = $result->where('emp_level','<',5);
		// }

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
			
		}

		if(trim($filter['search'])!=''){
			$result->where(function($query) use ($filter){
				$query->where('firstname','like','%'.$filter['search'].'%')
					->orWhere('lastname','like','%'.$filter['search'].'%')
					->orWhere('middlename','like','%'.$filter['search'].'%');
			});
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
		
		
		$result = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description,job_title_name'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('job_titles','job_titles.id','=','job_title_id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id');

		if($filter['division']!=0){
			$result = $result->where('division_id',$filter['division']);
		}

		if($filter['department']!=0 && $filter['department']!='' ){
			$result = $result->where('dept_id',$filter['department']);
		}

		if($filter['location']!=0 && $filter['location']!='' ){
			$result = $result->where('location_id',$filter['location']);
		}

		$result->where('pay_type','<>',3);
		$result->where('exit_status','=',1);
		$result->where('job_title_id','<>',130);

		$result->orderBy('lastname','asc');
		$result->orderBy('firstname','asc');

		return $result->get();
	}

	public function customReport()
	{
		$locations = DB::table('locations')->select()->get();
		
		foreach($locations as $location)
		{
			$divisions= DB::table('divisions')->select()->get();
			//echo $location->location_name .'<br>';
			$location->division = $divisions;

			foreach($location->division as $divs)
			{	
				//echo $divs->div_name .'<br>';
				$departments= DB::table('departments')->where('dept_div_id','=',$divs->id)->select()->get();
				$divs->departments = $departments;

				foreach($divs->departments as $div_departments)
				{
					//echo $div_departments->dept_name .'<br>';

					
					$employees = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description,job_title_name,location_altername AS location_id'))
						->leftJoin('departments','departments.id','=','dept_id')
						->leftJoin('divisions','divisions.id','=','division_id')
						->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
						->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
						->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
						->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id')
						->leftJoin('job_titles','job_titles.id','=','job_title_id')
						->leftJoin('locations','locations.id','=','employees.location_id')
						->where('pay_type','<>',3)
						->where('exit_status','=',1)
						->where('employees.dept_id','=',$div_departments->id)
						->where('location_id','=',$location->id)
						->where('division_id','=',$divs->id)
						->orderBy('lastname','asc')
						->orderBy('firstname','asc')
						->get();

					$div_departments->employees = $employees;
						
						// if($location->id==1 && $divs->id==3 && $div_departments->id ==7 ){
						// 	echo ($employees);
						// }

						
				}
			}
			
		}

			// dd($locations);
			
			return $locations;

	}

	public function customReportSG()
	{
		$locations = DB::table('locations')->select()->get();
		
		foreach($locations as $location)
		{
			$divisions= DB::table('divisions')->select()->get();
			//echo $location->location_name .'<br>';
			$location->division = $divisions;

			foreach($location->division as $divs)
			{	
				//echo $divs->div_name .'<br>';
				$departments= DB::table('departments')->where('dept_div_id','=',$divs->id)->select()->get();
				$divs->departments = $departments;

				foreach($divs->departments as $div_departments)
				{
					//echo $div_departments->dept_name .'<br>';

					
					$employees = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description,job_title_name,location_altername AS location_id'))
						->leftJoin('departments','departments.id','=','dept_id')
						->leftJoin('divisions','divisions.id','=','division_id')
						->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
						->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
						->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
						->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id')
						->leftJoin('job_titles','job_titles.id','=','job_title_id')
						->leftJoin('locations','locations.id','=','employees.location_id')
						->where('pay_type','=',3)
						->where('exit_status','=',1)
						->where('employees.dept_id','=',$div_departments->id)
						->where('location_id','=',$location->id)
						->where('division_id','=',$divs->id)
						->orderBy('lastname','asc')
						->orderBy('firstname','asc')
						->get();

					$div_departments->employees = $employees;
						
						// if($location->id==1 && $divs->id==3 && $div_departments->id ==7 ){
						// 	echo ($employees);
						// }

						
				}
			}
			
		}

			// dd($locations);
			
			return $locations;

	}

	public function generateReportWeekly($filter)
	{

		/*
		      select job_title__name from job_titles
      
      select * from employees left join job_titles on employees.job_title_id = job_titles.id
	  */
		$result = $this->model->select(DB::raw('employees.*,dept_code,div_code,emp_exit_status.status_desc,emp_emp_stat.estatus_desc,pay_description,job_title_name'))
		->leftJoin('departments','departments.id','=','dept_id')
		->leftJoin('divisions','divisions.id','=','division_id')
		->leftJoin('civil_status','employees.civil_status','=','civil_status.id')
		->leftJoin('emp_exit_status','exit_status','=','emp_exit_status.id')
		->leftJoin('emp_emp_stat','employee_stat','=','emp_emp_stat.id')
		->leftJoin('emp_pay_types','pay_type','=','emp_pay_types.id')
		->leftJoin('job_titles','employees.job_title_id','=','job_titles.id');

		if($filter['division']!=0){
			$result = $result->where('employees.division_id',$filter['division']);
		}

		if($filter['department']!=0){
			
			$result = $result->where('employees.dept_id',$filter['department']);
		}

		if($filter['location']!=0){
			$result = $result->where('employees.location_id',$filter['location']);
		}

		$result->where('pay_type','=',3);
		$result->where('exit_status','=',1);
		$result->where('job_title_id','<>',130);

		$result->orderBy('lastname','asc');
		$result->orderBy('firstname','asc');

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

	public function getLevels()
	{
		$result = $this->model->select('id','level_desc')->from('emp_level');
		return $result->get();
	}
	
	public function getJobTitles($dept_id)
	{
		//SELECT id,job_title_name FROM job_titles WHERE dept_id =9
		$result = $this->model->select('id','job_title_name')->from('job_titles')->where('dept_id',$dept_id);
		return $result->get();
	}

	public function getUserDept($bio_id)
	{
		$result = $this->model->select('dept_id')->where('biometric_id',$bio_id)->first();

		return $result;
	}

	public function employeeCount()
	{
		$array = [];
		$total = $this->model->select(DB::raw("count(*) as total"))->where('exit_status',1)->first();
		

		$reg = $this->model->select(DB::raw("count(*) as total"))->where('exit_status',1)->where('employee_stat',2)->first();
		$prob = $this->model->select(DB::raw("count(*) as total"))->where('exit_status',1)->where('employee_stat',1)->first();
		$support = $this->model->select(DB::raw("count(*) as total"))->where('exit_status',1)->where('employee_stat',3)->first();
		
		$array['total'] = $total->total;
		$array['reg'] = $reg->total;
		$array['prob'] = $prob->total;
		$array['support'] = $support->total;

		return $array;
	}

	public function getPosition($biometric_id){
        $result = $this->model->select('job_title_id')->where('biometric_id',$biometric_id);

		return $result->first();
    }

	public function generateBiometricAssignment()
	{
		//SELECT MIN(biometric_id),MAX(biometric_id) FROM employees;
		$range = $this->model->select(DB::raw("MIN(biometric_id) as r1,MAX(biometric_id) as r2"))->from('employees')->first();
		$empname = $this->model->select(DB::raw("biometric_id,CONCAT(lastname,', ',firstname) as empname"))->from('employees')->get();
		//dd($range->r1,$range->r2);

		// for($i=1;$i<=100000;$i++){
		// 	DB::table('biometric_series')->insert([
		// 		'biometric_id' => $i,
				
		// 	]);
		// }

		return array(
			'range' => $range,
			'empname' => $empname
		);
	}

	public function biometricIDGenerator()
	{
		$empname = $this->model->select(DB::raw("biometric_id,CONCAT(lastname,', ',firstname) as empname"))->from('employees')->get();
		
		$id_array = [];

		foreach($empname as $e){
			array_push($id_array,$e->biometric_id);
		}

		$flag = false;
		$index = 1;

		while($flag==false){
			if(in_array($index,$id_array)){
				$index++;
			}else{
				$flag = true;
			}
		}
		return $index;
	}

	public function createOn100($data)
	{
		// dd($data);
		// dd($data);
		$result = DB::connection('dps')->table('jlr_employees')
		->updateOrInsert([
			// 'biometrics_id' => $data['biometric_id'] 
			'last_name' => strtoupper($data['lastname']),
			'first_name' => strtoupper($data['firstname']),
		],[
			
			'middle_name' => strtoupper($data['middlename']),
		]);

		//$result = DB::connection('dps')->table('jlr_employees')->where('biometrics_id',$data['biometric_id'])->first();
		
	}

	public function generateWeeklyEmployeeQR($filter)
	{
		$location = DB::table('locations')->select();
		
		if($filter['location']!=0){
			$location->where('id',$filter['location']);
		}
		$location =	$location->get();

		foreach($location as $loc)
		{
			$employees = DB::table('employees')->where('location_id',$loc->id)
				->leftJoin('employee_names_vw','employee_names_vw.biometric_id','=','employees.biometric_id')
				->where('employees.exit_status',1)
				->where('pay_type',3)
				->select('employees.id','employee_name')
				->orderBy('lastname','asc')
				->orderBy('firstname','asc')
				->get();

			$loc->employees = $employees;
		}

		return $location;
	}

	public function headers()
	{
		$result = DB::table("masterlist_header")->orderBy('sort','asc');

		return $result->get();
	}

	function include($id)
	{
		DB::table('masterlist_header')->where('id','=',$id)->update([ 'include' => 'Y' ]); 
	}

	function remove($id)
	{
		DB::table('masterlist_header')->where('id','=',$id)->update([ 'include' => 'N' ]); 
	}

	function getHeader()
	{
		$result = DB::table('masterlist_header')->where('include','=','Y'); 

		return $result->get();
	}


	


}

/*
jlr_employees
last_name
first_name
middle_name
biometrics_id

  "firstname" => "Egllen"
  "lastname" => "Abalorio"
  "middlename" => "Gabenite"
*/