<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtherIncomeWeeklyAppHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\PayrollPeriodWeekly';
    protected $rules = [
    	
    ];

    public function find($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    public function list($type,$filter)
    {
        $result = $this->model->select('id','date_from','date_to','date_release',DB::raw("CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS drange"));

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

}