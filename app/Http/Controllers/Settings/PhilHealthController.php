<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\SettingsMapper\PhilHealthMapper;

class PhilHealthController extends Controller
{
    //
    private $mapper;

    public function __construct(PhilHealthMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.settings.phic-rate.index');
    }

    public function save(Request $request)
    {
        $array = array('id' => 1, 'rate' => $request->rate); 
        $result = $this->mapper->updateValid($array);

        return response()->json($result);
    }

    public function getPhicRate(Request $request)
    {
        $result = $this->mapper->getRate();

        return response()->json($result);
    }
}
