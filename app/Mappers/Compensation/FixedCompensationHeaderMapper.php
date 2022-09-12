<?php

namespace App\Mappers\Compensation;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixedCompensationHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Compensation\FixCompensationHeader';
    protected $rules = [
    	
    ];

    public function find($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    public function list($type,$filter)
    {
        $result = $this->model->select(DB::raw("compensation_fixed_headers.*,compensation_types.description,template,users.name as encoder"))
		->from('compensation_fixed_headers')
		->join('compensation_types','compensation_type','=','compensation_types.id')
		->join('payroll_period_vw','payroll_period_vw.id','=','compensation_fixed_headers.period_id')
        ->join('users','encoded_by','=','users.id')
        ;

        if($type!=0){
            $result = $result->where('compensation_type',$type);
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

    public function getFixeddComp()
    {
        $result = $this->model->select()
                ->from('compensation_types')
                ->whereRaw("is_fixed = 'Y'");
        return $result->get();
    }

    public function getPayrollPeriod()
    {
        $result = $this->model->select()->from('payroll_period_vw')->orderBy('id','desc');

        return $result->get();
    }

}