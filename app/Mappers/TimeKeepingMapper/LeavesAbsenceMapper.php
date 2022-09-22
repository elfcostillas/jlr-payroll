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
}