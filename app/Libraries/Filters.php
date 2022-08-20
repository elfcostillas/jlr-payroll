<?php
namespace App\Libraries;

use Illuminate\Database\Eloquent\Builder;

class Filters
{
    const FILTER    = 'filter';
    const SORT      = 'sort';

    public function queryOps(Builder $select, $params)
    {
        if(is_array($params['filter'])){
            $select = $this->filter($select, $params['filter']);
        }

        if(is_array($params['sort'])){
            $select = $this->sort($select, $params['sort']);
        }
        return $select;
    }

    public function filter(Builder $select, $data)
    {
        if(is_array($data['filters'])) 
		{
			foreach($data['filters'] as $i=>$f)
			{
				$op = $f['field'];
				switch($f['operator']) 
				{
					case 'eq':
						$select->where->equalTo($op, $f['value']); 
						break;
					case 'contains':
                        // $select->where->like($op, $f['value']);
                        $select->where($op, 'iLIKE', '%' . $f['value'] . '%');
						break;
				}
				// return $select;
			}
        }
        return $select;
    }

    public function sort(Builder $select, $data)
    {
        foreach($data as $sorts)
        {
            $select->orderBy($sorts['field'], $sorts['dir']);
        }
        return $select;
    }
}