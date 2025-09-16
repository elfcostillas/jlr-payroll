<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManualDTRHeaderMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\ManualDTRHeader';
    protected $rules = [
        'biometric_id' => 'required|sometimes',
        'remarks' => 'required|sometimes',
        'period_id' => 'required|sometimes|gt:0',
        //'encoded_by',
        //'encoded_on',
        //'date_from' => ['required','sometimes',],
        //'date_to' => 'required|sometimes',
    ];

    protected $messages = [
        'period_id.gt' => 'Please select payroll period.'
    ];

    public function header($id){
		return $this->model->find($id);
    }

    public function list($filter)
    {
        $result = $this->model->select(DB::raw("manual_dtr.id,manual_dtr.biometric_id,manual_dtr.remarks,manual_dtr.period_id,doc_status,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,users.name,payroll_period_weekly.date_from,payroll_period_weekly.date_to"))
        ->from('manual_dtr')
        ->join('payroll_period_weekly','manual_dtr.period_id','=','payroll_period_weekly.id')
        ->join('employees','manual_dtr.biometric_id','=','employees.biometric_id')
        ->join('users','encoded_by','=','users.id');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('manual_dtr.id','DESC');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        //return $result->get();
    }

    public function emplist()
    {
        $result = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname"))
                ->from('employees')
                ->where('exit_status',1)
                ->where('pay_type',3)
                ->orderBy('lastname')
                ->orderBy('firstname');

        return $result->get();
    }

    public function validateDate($date,$biometric_id)
    {
        //SELECT MIN(date_from) as dfrom,MAX(date_to) as dto FROM manual_dtr WHERE biometric_id = 352
        $range = $this->model->select(DB::raw("MIN(date_from) as dfrom,MAX(date_to) as dto"))->where('biometric_id',$biometric_id)->first();
        
       
        if($range->dfrom && $range->dto){
            $startDate = Carbon::createFromFormat('Y-m-d',$range->dfrom);
            $endDate = Carbon::createFromFormat('Y-m-d',$range->dto);
            $toCheck = Carbon::createFromFormat('Y-m-d',$date);
    
            $check =  $toCheck->between($startDate,$endDate);
        }else {
            $check = false;
        }
       
        
        return $check;
    }

    public function printHeader($id)
    {
        //SELECT manual_dtr.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),'-',DATE_FORMAT(date_to,'%m/%d/%Y')) AS periodrange 
        //FROM manual_dtr INNER JOIN employees ON manual_dtr.biometric_id = employees.biometric_id
        $result = $this->model->select(DB::raw("job_title_name,dept_name,manual_dtr.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,CONCAT(DATE_FORMAT(payroll_period_weekly.date_from,'%b %d, %Y'),' - ',DATE_FORMAT(payroll_period_weekly.date_to,'%b %d, %Y')) AS periodrange"))
                ->from('manual_dtr')
                ->join('payroll_period_weekly','manual_dtr.period_id','=','payroll_period_weekly.id')
                ->join('employees','manual_dtr.biometric_id','=','employees.biometric_id')
                ->leftJoin('departments','employees.dept_id','=','departments.id')
                ->leftJoin('divisions','divisions.id','=','division_id')
                ->leftJoin('job_titles','job_titles.id','=','job_title_id')
                ->where('manual_dtr.id',$id);

        return $result->first();
    }

    public function openWeeklyPeriod()
    {
        $result = $this->model->select(DB::raw("id,date_from,date_to,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) template "))
                    ->from("payroll_period_weekly")
                    ->orderBy("id","desc");

        return $result->get();
    }

    public function getPeriodByID($id)
    {
        $result = $this->model->select(DB::raw("id,date_from,date_to"))
        ->from("payroll_period_weekly")
        ->where('id',$id);

        return $result->first();
    }


}

   

/*
LEFT JOIN job_titles ON job_titles.id = employees.job_title_id
LEFT JOIN departments ON employees.dept_id = departments.id
LEFT JOIN divisions ON divisions.id = division_id


SELECT manual_dtr.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,users.name FROM manual_dtr
INNER JOIN employees ON manual_dtr.biometric_id = employees.biometric_id
INNER JOIN users ON encoded_by = users.id;
*/