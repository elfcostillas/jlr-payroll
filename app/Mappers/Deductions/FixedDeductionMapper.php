<?php

namespace App\Mappers\Deductions;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixedDeductionMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Deductions\FixedDeduction';
    protected $rules = [
        'period_id' => 'sometimes|required',
        'biometric_id' => 'sometimes|required',
        'deduction_type' => 'sometimes|required',
        //'remarks' => 'required|sometimes',
        'amount' => 'sometimes|required|gt:0',
    ];

    public function list($type,$filter)
    {
        $result = $this->model->select(DB::raw("deduction_fixed.*,employee_name,users.name as encoder,payroll_period_vw.template as period_range,
        deduction_types.description as deduction_desc"))
		->from('deduction_fixed')
		->join('employee_names_vw','employee_names_vw.biometric_id','=','deduction_fixed.biometric_id')
		->join('payroll_period_vw','payroll_period_vw.id','=','deduction_fixed.period_id')
		->join('deduction_types','deduction_type','=','deduction_types.id')
        ->join('users','encoded_by','=','users.id');
        
        if($type!=0){
            $result = $result->where('deduction_type',$type);
        }

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				if($f['field']=='biometric_id'){
                    $result->where('employee_names_vw.employee_name','like','%'.$f['value'].'%');
                }else{  
                    $result->where($f['field'],'like','%'.$f['value'].'%');
                }
                
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

    public function header($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    public function getTypes()
    {
        //SELECT id,description FROM deduction_types WHERE is_fixed = 'N'
        $result = $this->model->select('id','description')->from('deduction_types')
        ->whereRaw('is_fixed = \'Y\'');;

        return $result->get();
    }

    public function getPayrollPeriod()
    {
        $result = $this->model->select()->from('payroll_period_vw')->orderBy('id','desc');

        return $result->get();
    }
    
    public function searchEmployee($filter)
	{	
		
		$result = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS employee_name"))
					->from('employees');
	
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


/*

SELECT * FROM deduction_fixed 
INNER JOIN employee_names_vw ON employee_names_vw.biometric_id = deduction_fixed.biometric_id
INNER JOIN payroll_period_vw ON deduction_fixed.period_id = payroll_period_vw.id
INNER JOIN deduction_types ON deduction_type = deduction_types.id
*/