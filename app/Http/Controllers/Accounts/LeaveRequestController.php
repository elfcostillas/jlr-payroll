<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Accounts\LeaveRequestHeaderMapper;

class LeaveRequestController extends Controller
{
    //
    private $header;

    public function __construct(LeaveRequestHeaderMapper $header)
    {
        $this->header = $header;
    }

    public function index()
    {
        return view('app.accounts.leave-request.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->header->list($filter);

        return response()->json($result);
    }

    public function save(Request $request)
    {

    }

    public function readHeader(Request $request)
    {

    }

    public function readDetails(Request $request)
    {

    }

    public function updateDetails(Request $request)
    {

    }
}


/*
SELECT * FROM leave_request_header INNER JOIN employees ON leave_request_header.biometric_id = employees.biometric_id
LEFT JOIN divisions ON employees.division_id = divisions.id
LEFT JOIN departments ON employees.dept_id = departments.id
LEFT JOIN job_titles ON employees.job_title_id = job_titles.id;


SELECT * FROM leave_request_header 
INNER JOIN employees ON leave_request_header.biometric_id = employees.biometric_id
LEFT JOIN divisions ON leave_request_header.division_id = divisions.id
LEFT JOIN departments ON leave_request_header.dept_id = departments.id
LEFT JOIN job_titles ON leave_request_header.job_title_id = job_titles.id
LEFT JOIN leave_request_type ON leave_type_code = leave_type
LEFT JOIN employees AS approver ON approver.biometric_id = acknowledge_by
LEFT JOIN employees AS hr_staff ON hr_staff.biometric_id = received_by
ORDER BY leave_request_header.id

*/

