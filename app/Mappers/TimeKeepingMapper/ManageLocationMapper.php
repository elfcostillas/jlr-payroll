<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class ManageLocationMapper extends AbstractMapper {

    protected $modelClassName = 'App\Models\Timekeeping\TMPLocation';
    protected $rules = [
        // 'biometric_id' => 'required|sometimes',
        // 'dtr_date' => 'required|sometimes',
    ];

    protected $messages = [
        
    ];

    public function listPeriod()
    {
        $result = $this->model->select(DB::raw('payroll_period_weekly.*'))
                ->join('payroll_period_weekly','payroll_period_weekly.id','=','weekly_tmp_locations.period_id')
                ->distinct()
                ->orderBy('payroll_period_weekly.id','DESC');
                // ->groupBy('payroll_period_weekly.period_id');

        $total = $result->count();
        
        return [
            'total' => count($result->get()),
            'data' => $result->get()
        ];
    }

    public function employeeList($period_id,$filter)
    {
        $result = $this->model->select('weekly_tmp_locations.period_id','weekly_tmp_locations.id','weekly_tmp_locations.biometric_id','employee_name as empname','weekly_tmp_locations.loc_id','location_name')
            ->join('employee_names_vw','employee_names_vw.biometric_id','=','weekly_tmp_locations.biometric_id')
            ->join('employees','weekly_tmp_locations.biometric_id','=','employees.biometric_id')
            ->join('locations','locations.id','=','weekly_tmp_locations.loc_id')
            ->where('weekly_tmp_locations.period_id','=',$period_id);

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

        if(trim($filter['search'])!=''){
           
            $result->where(function($query) use ($filter){
                $query->where('employee_name','like','%'.$filter['search'].'%');
                    // ->orWhere('middlename','like','%'.$filter['search'].'%');
            });
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

    public function ListEmployeeByLocation($period_id) 
    {
        $location = DB::table('locations')->select();
		
		$location =	$location->get();

        foreach($location as $loc)
		{
            
            $employees = $this->model->select('weekly_tmp_locations.period_id','weekly_tmp_locations.id','weekly_tmp_locations.biometric_id','employee_name as employee_name','weekly_tmp_locations.loc_id','location_name')
            ->join('employee_names_vw','employee_names_vw.biometric_id','=','weekly_tmp_locations.biometric_id')
            ->join('employees','weekly_tmp_locations.biometric_id','=','employees.biometric_id')
            ->join('locations','locations.id','=','weekly_tmp_locations.loc_id')
            ->where('weekly_tmp_locations.period_id','=',$period_id)
            ->where('weekly_tmp_locations.loc_id',$loc->id)
            ->get();
        
            $loc->employees = $employees;
        }

       

        return $location;
    }
}