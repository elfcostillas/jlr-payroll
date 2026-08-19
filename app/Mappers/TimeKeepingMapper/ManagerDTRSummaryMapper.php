<?php

namespace App\Mappers\TimeKeepingMapper;
use App\Mappers\Mapper as AbstractMapper;
use Illuminate\Support\Facades\DB;

class ManagerDTRSummaryMapper extends AbstractMapper
{

    protected $modelClassName = 'App\Models\Timekeeping\DTRSummary';
    protected $rules = [

    ];

    public function employeeList($period_id,$filter)
    {
        $result = $this->model->select(DB::raw("edtr_totals.*,employee_names_vw.employee_name, (ndays+vl_wp+vl_wop+sl_wp+sl_wop+bl+awol+brv+reghol_pay+sphol_pay+dblhol_pay+dblsphol_pay+svl+mpl) as total_ndays"))
            ->join('employee_names_vw','employee_names_vw.biometric_id','=','edtr_totals.biometric_id')
            ->join('employees','employees.biometric_id','=','employee_names_vw.biometric_id')
            ->where('edtr_totals.period_id','=',$period_id);

        if (!array_key_exists("pageSize",$filter))
        {
            $filter['pageSize'] = 999;
        }else {
            $filter['pageSize'] = $filter['pageSize'] ?? 999;
        }
          
        if (array_key_exists("filter",$filter))
        {
            if($filter['filter']!=null){
                foreach($filter['filter']['filters'] as $f)
                {
                    if($f['field']!='empname'){
                        $result->where($f['field'],'like','%'.$f['value'].'%');
                    }else {
                        $result->where('employee_name','like','%'.$f['value'].'%');
                    }
                
                }
                
            }
        }

        if (array_key_exists("emp_level",$filter))
        {
            switch($filter['emp_level']){
                case 'confi':
                    $result->where('employees.emp_level','<',5);
                    break;

                case 'non-confi':
                    $result->where('employees.emp_level','=',5);
                    break;

                default:
                   
                    break;
                
            }
        }

        if (array_key_exists("pay_type",$filter))
        {
            switch($filter['pay_type']){
                case 'daily':
                    $result->where('employees.pay_type','=',2);
                    break;

                case 'semi-monthly':
                    $result->where('employees.pay_type','=',1);
                    break;

                default:
                   
                    break;
                
            }
        }

            
        if (array_key_exists("search",$filter))
        {
            if(trim($filter['search'])!=''){
           
                $result->where(function($query) use ($filter){
                    $query->where('employee_name','like','%'.$filter['search'].'%');
                        // ->orWhere('middlename','like','%'.$filter['search'].'%');
                });
            }    
        }

        $total = $result->count();

        $result->limit($filter['pageSize'])->skip($filter['skip'])
		->orderBy('lastname','ASC')
		->orderBy('firstname','ASC');

        return [
            'total' => $total,
            'data' => $result->get()
        ];
    }
}
