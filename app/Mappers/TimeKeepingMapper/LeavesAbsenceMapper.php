<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeavesAbsenceMapper extends AbstractMapper {
    protected $modelClassName = 'App\Models\Accounts\LeaveRequestHeader';
   
    protected $rules = [
    	
    ];

    public function header($id){
        return $this->model->find($id);
    }

    public function list($filter)
    {
        $result = $this->model->select()->from('leave_request_vw');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])
		->orderBy('id','Desc');

		return [
			'total' => $total,
			'data' => $result->get()
		];

    }

	public function getLeavesFrom100(){

		//dps //mysql
		$leaves = DB::connection('dps')->table('hr_leave')
							->select(DB::raw('biometrics_id,hr_leave.*'))
							->leftjoin('jlr_employees','o1_id','=','emp_id')
							->where('inclusive_from','>=','2022-09-01')
							->get();

		/*
		jlr_employees ON o1_id = emp_id
		*/

		return $leaves;
	}

	public function getEncodedLeaveCredits($year)
	{
		$result = $this->model->select()->from('leave_credits')->where('fy_year',$year)
		->leftJoin('employee_names_vw','leave_credits.biometric_id','=','employee_names_vw.biometric_id');
		
		return $result->get();
	}

	public function getOldId($biomettic_id)
	{
		$id = DB::connection('dps')->table('jlr_employees')->select('o1_id')->where('biometrics_id',$biomettic_id)->first();

		return $id;

	}

}