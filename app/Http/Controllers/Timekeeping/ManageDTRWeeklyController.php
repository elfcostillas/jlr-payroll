<?php

namespace App\Http\Controllers\Timekeeping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageDTRWeeklyController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        return view('app.timekeeping.manage-dtr-weekly.index');

    }


}
