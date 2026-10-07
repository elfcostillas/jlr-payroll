<?php

namespace App\Mappers\Reports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QPIPMapper
{
    public $quarter_arr ;

    public function mainQuery($quarter_dates, $year,$emp_level)
    {
       
        $qry =  DB::table('employees')
            ->join('divisions', 'divisions.id', '=', 'employees.division_id')
            ->join('departments', 'departments.id', '=', 'employees.dept_id')
            ->whereNotNull('employees.date_regularized')
            ->where('employees.date_regularized', '<', $quarter_dates['date_to'])
            ->where(function ($query) use ($quarter_dates) {
                $query->whereNull('employees.exit_date')
                    ->orWhere('employees.exit_date', '>', $quarter_dates['date_from']);
            })
            ->select([
                'divisions.div_name',
                'departments.dept_name',
                'employees.division_id',
                'employees.dept_id',
                'employees.biometric_id',
                'employees.lastname',
                'employees.firstname',
                'employees.middlename',
                'employees.suffixname',
                'employees.date_regularized',
                'employees.exit_date',
                'date_hired'
            ]);

            switch ($emp_level) {
                case 'sg':
                     $qry =  DB::table('employees')
                        ->join('divisions', 'divisions.id', '=', 'employees.division_id')
                        ->join('departments', 'departments.id', '=', 'employees.dept_id')
                        ->whereNotNull('employees.date_hired')
                        ->whereRaw("DATE_ADD(date_hired,INTERVAL 180 DAY) < ?",[$quarter_dates['date_to'] ])
                        // ->where('employees.date_regularized', '<', $quarter_dates['date_to'])
                        ->where(function ($query) use ($quarter_dates) {
                            $query->whereNull('employees.exit_date')
                                ->orWhere('employees.exit_date', '>', $quarter_dates['date_from']);
                        })
                        ->where('employees.emp_level', '=', 6)
                        ->select([
                            'divisions.div_name',
                            'departments.dept_name',
                            'employees.division_id',
                            'employees.dept_id',
                            'employees.biometric_id',
                            'employees.lastname',
                            'employees.firstname',
                            'employees.middlename',
                            'employees.suffixname',
                            'employees.date_regularized',
                            'employees.exit_date',
                            'date_hired',

                        ]);

                    $qry->where('employees.emp_level', '=', 6);

                    break;
               
                case 'ranknfile':
                    $qry->where('employees.emp_level', '=', 5);
                    break;

                case 'confi':   
                    $qry->where('employees.emp_level', '<', 5);
                    break;
            }
        return $qry;
    }

    public function buildSGData($quarter, $year)
    {
        $this->quarter_arr = [
            1 => ['date_from' => $year.'-01-01', 'date_to' => $year.'-03-31'],
            2 => ['date_from' => $year.'-04-01', 'date_to' => $year.'-06-30'],
            3 => ['date_from' => $year.'-07-01', 'date_to' => $year.'-09-30'],
            4 => ['date_from' => $year.'-10-01', 'date_to' => $year.'-12-31']
        ];

        $quarter_dates = $this->quarter_arr[$quarter];

        $main = $this->mainQuery($quarter_dates, $year,'sg');

        $divisions_collection_qry = $main;

        $divisions_collection = $divisions_collection_qry->clone()->select('divisions.div_name', 'employees.division_id')->distinct()->get();

        foreach ($divisions_collection as $division) {
          
            $departments_qry = $divisions_collection_qry->clone()->where('employees.division_id', $division->division_id);

          
            
            $departments_collection = $departments_qry->clone()->select('departments.dept_name', 'employees.dept_id')->distinct()->get();
                    
                foreach ($departments_collection as $department) {
                    
                    $employees_collection = $departments_qry->clone()->where('employees.dept_id', $department->dept_id)->get();

                    /* assigned data here */
                    foreach($employees_collection as $employee)
                    {
                        $employee->data = $this->buildDataForEmployee($employee,$quarter,$year);
                    }

                    $department->employees = $employees_collection;
                }

            $division->departments = $departments_collection;
        }

        return $divisions_collection;

    }

    public function getYears()
    {
        // $years = []; // select distinct year(dtr_date) as dtr_year from edtr_detailed order by dtr_year asc;
        // return $years;
        $result = DB::table('edtr_detailed')
            ->select(DB::raw('DISTINCT YEAR(dtr_date) as dtr_year'))
            ->orderBy('dtr_year', 'desc')
            ->get();

        return $result;
    }

    public function getEmployeesByDept($level)
    {
        $result = DB::table('employees')
            ->select('department', DB::raw('COUNT(*) as employee_count'))
            ->groupBy('department')
            ->get();

        return $result;
    }

    public function getMonths($quarter, $year)
    {
        // $quarter_arr = [
        //     1 => ['January', 'February', 'March'],
        //     2 => ['April', 'May', 'June'],
        //     3 => ['July', 'August', 'September'],
        //     4 => ['October', 'November', 'December']
        // ];

        $quarter_arr = [
            1 => array(1 => 'January', 2 => 'February', 3 => 'March' ),
            2 => array(4 => 'April', 5 => 'May',6 => 'June'),
            3 => array(7 => 'July', 8 => 'August', 9 => 'September'),
            4 => array(10 => 'October', 11 => 'November' , 12 => 'December')
        ];

       
        return $quarter_arr[$quarter];
    
    }

    public function buildDataForEmployee($employee,$quarter,$year)
    {
        $quarter_months = $this->getMonths($quarter,$year);

        /* make months*/

        $data  = array();

        foreach($quarter_months as $key => $m)
        {
            $date_from = Carbon::createFromDate($year,$key,1);
            $date_to = $date_from->format('Y-m-t');

            // dd($date_from->format('Y-m-d'),$date_to);

            $att = DB::table('edtr_detailed')
                ->where('biometric_id',$employee->biometric_id)
                ->whereBetween('dtr_date',[$date_from->format('Y-m-d'),$date_to])
                ->select(DB::raw("sum(IF(late>0 ,1,0)) as tardy,sum(round(under_time/60/8,2)) as ut,SUM(ROUND(awol/8,2)) as awol"))
                ->first();

            $data[$key] = array(
                'tardy' => (float) $att->tardy,
                'sil' => 0,
                'lwop' => 0,
                'ut' =>  (float) $att->ut,
                'sus' => 0,
                'awol' => (float) $att->awol,
            );
        }

        return $data;
    }
}
