<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\ManualDTRHeaderMapper;
use App\Mappers\TimeKeepingMapper\ManualDTRDetailMapper;
use Illuminate\Support\Facades\Auth;

class ManualDTRController extends Controller
{
    //
    private $header;
    private $detail;

    public function __construct(ManualDTRHeaderMapper $header,ManualDTRDetailMapper $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    public function index()
    {
        return view('app.timekeeping.manual-dtr.index');
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

    public function getEmployees(Request $request)
    {
        $result = $this->header->emplist();
        return response()->json($result);
    }

    public function save(Request $request)
    {   
        $user = Auth::user();

        $data = json_decode($request->data);

        $data_arr = (array) $data;

        array_push($data_arr,[
            'encoded_by' => $user->id,
            'encoded_on' => now()
        ]);

        $result = $this->header->insertValid($data_arr);

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}
        return response()->json($result);
    }
}
