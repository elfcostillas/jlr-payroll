<?php

namespace App\Mappers\Deductions;
use App\Mappers\Mapper as AbstractMapper;
use Illuminate\Support\Facades\DB;

class GovtLoanSGMapper extends AbstractMapper 
{
    //

    protected $modelClassName = 'App\Models\Deductions\GovtLoanSG';
    
    protected $rules = [

        'period_id'=> 'sometimes|required',
        'biometric_id'=> 'sometimes|required',
        'deduction_type'=> 'sometimes|required',
        'remarks'=> 'sometimes|required',
        'total_amount'=> 'sometimes|required|gt:0',
        'terms'=> 'sometimes|required|gt:0',
        'ammortization'=> 'sometimes|required|gt:0',
        'is_stopped'=> 'sometimes|required',
        'encoded_by'=> 'sometimes|required',
        'encoded_on'=> 'sometimes|required',

    ];

    public function header($id)
    {
        $result = $this->model->find($id);
        return $result;
    } 
    
    public function list($biometric_id,$filter)
    {
        $result = $this->model->select(DB::raw("deduction_gov_loans_sg.id,employee_names_vw.employee_name,loan_types.description,total_amount,ammortization"))
		->from('deduction_gov_loans_sg')
		->join('employee_names_vw','employee_names_vw.biometric_id','=','deduction_gov_loans_sg.biometric_id')
		->join('payroll_period_sg_vw','payroll_period_sg_vw.id','=','deduction_gov_loans_sg.period_id')
		->join('loan_types','deduction_type','=','loan_types.id')
        ->join('users','encoded_by','=','users.id');
        
        if($biometric_id!=0){
            $result = $result->where('deduction_gov_loans_sg.biometric_id',$biometric_id);
        }

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				// if($f['field']=='biometric_id'){
                //     $result->where('employee_names_vw.employee_name','like','%'.$f['value'].'%');
                // }else{  
                    $result->where($f['field'],'like','%'.$f['value'].'%');
                //}
                
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


    public function employeelist($filter)
    {
        $result = $this->model->select()->from('employee_names_vw')
        ->where('exit_status',1)
        ->where('emp_level',6);

        if($filter['filter']!=null){
            if(array_key_exists('filters',$filter['filter'])){
                foreach($filter['filter']['filters'] as $f)
                {
                    $result->where($f['field'],'like','%'.$f['value'].'%');
                }
            }
		}

        $total = $result->count();

		//$result->limit($filter['pageSize'])->skip($filter['skip']);

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

    public function getPayrollPeriod()
    {
        $result = $this->model->select()->from('payroll_period_sg_vw')->orderBy('id','desc');

        return $result->get();
    }

    public function getTypes()
    {
        //SELECT id,description FROM loan_types WHERE is_fixed = 'N'
        $result = $this->model->select('id','description')->from('loan_types');
        return $result->get();
    }

    public function searchEmployee($filter)
	{	
		
		$result = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS employee_name"))
					->from('employees')
                    ->where('emp_level','>',5);
	
		if($filter!=null){
			if(array_key_exists('filters',$filter)){
				foreach($filter['filters'] as $f)
				{
					$result->where('lastname','like','%'.$f['value'].'%')
					->orWhere('firstname','like','%'.$f['value'].'%');
				}
			}
		}

		return $result->get();
		
	}

}
