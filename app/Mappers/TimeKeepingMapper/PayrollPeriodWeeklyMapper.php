<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollPeriodWeeklyMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriodWeekly';
    protected $rules = [
    	
    ];

    public function list($filter)
    {
        $result = $this->model->select('id','date_from','date_to','date_release','man_hours');

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

       
    }

	public function listforDropDown()
	{	
		// /SELECT id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS drange FROM payroll_period_weekly ORDER BY id DESC
		//SELECT period_id FROM payrollregister_posted_weekly;

		$posted = $this->model->select('period_id')->from('payrollregister_posted_weekly')->distinct()->get();

		$result = $this->model->select(DB::raw("id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS drange"))
								->whereNotIn('id',$posted->pluck('period_id'))
								->orderBy('id','DESC');

		return $result->get();
	}


}
