<?php

namespace App\Mappers\Memo;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TardinessMemoMapper extends AbstractMapper {

	protected $modelClassName = 'App\Models\Memo\TardinessMemo';
    protected $rules = [
        

    ];

    public function list($filter)
    {
        //SELECT id,description FROM deduction_types
        $result = $this->model->select('id','memo_to','memo_from','memo_date','memo_subject')->from('tardiness_memo');

        if($filter['filter']!=null){
			foreach($filter['filter']['filters'] as $f)
			{
				$result->where($f['field'],'like','%'.$f['value'].'%');
			}
		}

		$total = $result->count();

		$result->limit($filter['pageSize'])->skip($filter['skip'])->orderBy('id','desc');

		return [
			'total' => $total,
			'data' => $result->get()
		];

        return $result->get();
    }

    public function readMemo($id)
    {
        if($id==0){
            $result = $this->model->select('id',
            'biometric_id',
            'memo_to',
            'memo_from',
            'memo_date',
            'memo_subject',
            'memo_upper_body',
            'memo_lower_body',
            'prep_by_text',
            'prep_by_name',
            'prep_by_position',
            'noted_by_text',
            'noted_by_name',
            'noted_by_position',
            'noted_by_text_dept',
            'noted_by_name_dept',
            'noted_by_position_dept')
            ->from('tardiness_memo_template')
            ->where('id',1);

        }else{
            $result = $this->model->select('id','biometric_id','memo_to','memo_from','memo_date','memo_subject',
            'memo_upper_body','memo_lower_body','prep_by_text','prep_by_name','prep_by_position','noted_by_name_dept',
            'noted_by_text','noted_by_name','noted_by_position','noted_by_text_dept','noted_by_position_dept')
            ->from('tardiness_memo')
            ->where('id',$id);
        }

        return $result->first();
    }

    public function getNames()
    {
        $qry = "SELECT biometric_id,CONCAT(TRIM(lastname),', ',TRIM(firstname),' ',CASE 
        WHEN middlename IS NULL THEN ''
        WHEN middlename = '' THEN ''
        ELSE CONCAT(SUBSTR(middlename,1,1),'. ') 
        END,IFNULL(suffixname,'') ) AS employee_name
        FROM employees WHERE exit_status = 1
        ORDER BY lastname,firstname";

        $result = DB::select($qry);

        return $result;
    }

}
