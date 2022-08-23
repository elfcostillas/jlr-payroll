<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\TimeKeepingMapper\HolidayMapper;
use App\Mappers\TimeKeepingMapper\HolidayLocationMapper;
class HolidayController extends Controller
{
    //
    private $holiday_type;
    
    private $mapper;
    private $location;

    public function __construct(HolidayMapper $mapper,HolidayLocationMapper $location)
    {
        // $holiday_type = [
        //     'label' => 'Legal Holiday','value' => 'LH',
        //     'label' => 'Special Holiday','value' => 'SH',
        //     'label' => 'Double Legal Holiday','value' => 'DLH',
        //     'label' => 'Company Holiday','value' => 'CH',
        // ];

        // $this->holiday_type = $holiday_type;
        $this->mapper = $mapper;
        $this->location = $location;
    }

    public function index()
    {
        //$types = $this
        $locations = $this->mapper->getLocations();
        return view('app.timekeeping.holiday.index',['locations'=>$locations]);
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

    public function create(Request $request)
    {
        $result = $this->mapper->insertValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $result = $this->mapper->updateValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function  showLocation(Request $request)
    {
        $result = $this->location->showHolidayLocations($request->holiday_id);

        return response()->json(['locations'=>$result]);
    }
    public function  createLocation(Request $request)
    {
        $result = $this->location->insertValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }
    public function  destroyLocation(Request $request)
    {
        $result = $this->location->updateValid($request->all());

        if(is_object($result)){
			return response()->json($result)->setStatusCode(500, 'Error');
		}

        return response()->json($result);
    }

    public function getHolidayTypes()
    {
        $result = $this->mapper->getHolidayTypes();

        return response()->json($result);
    }
}
