<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Mappers\TimeKeepingMapper\LeavesAbsenceMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

class LeavesAbsencesController extends Controller
{
    //
    private $mapper;

    public function __construct(LeavesAbsenceMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.timekeeping.leave-absence.index');
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

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function receive(Request $request)
    {
        $user = Auth::user();
        $leave = $this->mapper->header($request->id);

        if($leave!=null){
            $leave->received_by= $user->id;
            $leave->received_time= now();
        }

        $result = $this->mapper->updateValid($leave->toArray());

        return response()->json($result);

    }

	public function getLeavesFrom100(){
        $result = $this->mapper->getLeavesFrom100();

        
    }
}
