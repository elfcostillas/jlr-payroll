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
        //'encoded_by',
        //'encoded_on',
        'date_from' => 'required|sometimes',
        'date_to' => 'required|sometimes',
    ];

    public function list($filter)
    {
        $result = $this->model->select(DB::raw("manual_dtr.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,users.name"))
        ->from('manual_dtr')
        ->join('employees','manual_dtr.biometric_id','=','employees.biometric_id')
        ->join('users','encoded_by','=','users.id');

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

    public function emplist()
    {
        $result = $this->model->select(DB::raw("biometric_id,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname"))
                ->from('employees')
                ->where('exit_status',1)
                ->orderBy('lastname')
                ->orderBy('firstname');

        return $result->get();
    }


}

/*

SELECT manual_dtr.*,CONCAT(IFNULL(lastname,''),', ',IFNULL(firstname,''),' ',IFNULL(suffixname,'')) AS empname,users.name FROM manual_dtr
INNER JOIN employees ON manual_dtr.biometric_id = employees.biometric_id
INNER JOIN users ON encoded_by = users.id;
*/