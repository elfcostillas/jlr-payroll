<?php

namespace App\Mappers\Deductions;
use App\Mappers\Mapper as AbstractMapper;

class GovtLoanSGMapper extends AbstractMapper 
{
    //

    protected $modelClassName = 'App\Models\Deductions\GovtLoanSG';

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'biometric_id',
        'deduction_type',
        'remarks',
        'total_amount',
        'terms',
        'ammortization',
        'is_stopped',
        //'deduction_sched',
        'encoded_by',
        'encoded_on',
        'loan_amount'
    ];

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

}
