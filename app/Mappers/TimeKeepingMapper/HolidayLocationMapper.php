<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HolidayLocationMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\HolidayLocation';
    protected $rules = [
    	
    ];

    public function list($filter)
    {
        //$result = $this->model->select('id','date_from','date_to','date_release','man_hours');
        //SELECT `holidays`.id,`holiday_date`,`holiday_remarks`,`holiday_type`,`type_description` FROM holidays INNER JOIN holiday_types ON holidays.`holiday_type` = `holiday_types`.id

        $result = $this->model->select('holidays.id','holiday_date','holiday_remarks','holiday_type','type_description')
                            ->from('holidays')
                            ->join('holiday_types','holidays.holiday_type','=','holiday_types.id');

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

    public function showHolidayLocations($id)
    {
       
        $result = $this->model->select('id','holiday_id','location_id')->where('holiday_id',$id);
        return $result->get();
    }

    public function findLastWorkingDay($holiday)
    {
        
        $result = $this->model->select()->from('holidays')->where('id',$holiday['holiday_id'])->first();
        $ldow = Carbon::createFromFormat('Y-m-d',$result->holiday_date);

        $flag = true; $ctr = 0;

        do{
            $ldow->subDay();

            $holidays =  $this->model->select()->from('holidays')->join('holiday_location','holiday_id','=','holidays.id')
            ->where('holiday_date','=',$ldow->format('Y-m-d'))->where('location_id','=',$holiday['location_id'])->count();

        
            if($ldow->shortEnglishDayOfWeek!='Sun' && $holidays == 0){
                $flag = false;
            }

            $ctr++;
            if($ctr>=15){
                $flag = false;
            }

        }while($flag);

        return $ldow->format('Y-m-d');
    }


}


//SELECT * FROM holidays 
//INNER JOIN holiday_location ON holiday_id = holidays.id WHERE holiday_date = '2022-08-06' AND location_id = 