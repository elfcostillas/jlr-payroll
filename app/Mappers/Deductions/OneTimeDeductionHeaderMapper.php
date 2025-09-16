<?php

namespace App\Mappers\Deductions;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OneTimeDeductionHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Deductions\OneTimeDeductionHeader';
    protected $rules = [
    	'period_id' => 'required|sometimes',
        'deduction_type' => 'required|sometimes',
        'remarks' => 'required|sometimes',
    ];

    public function list($type,$filter)
    {
        $result = $this->model->select(DB::raw("deduction_onetime_headers.*,deduction_types.description,template,users.name as encoder"))
		->from('deduction_onetime_headers')
		->join('deduction_types','deduction_type','=','deduction_types.id')
		->join('payroll_period_vw','payroll_period_vw.id','=','deduction_onetime_headers.period_id')
        ->join('users','encoded_by','=','users.id')
        ;

        if($type!=0){
            $result = $result->where('deduction_type',$type);
        }

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

    public function header($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    public function getTypes()
    {
        //SELECT id,description FROM deduction_types WHERE is_fixed = 'N'
        $result = $this->model->select('id','description')->from('deduction_types')
        ->whereRaw('is_fixed = \'N\'');;

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
