<?php

namespace App\Http\Controllers\Compentsations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtherIncomeWeeklyAppController extends Controller
{
    //

    public function __construct()
    {

    }

    public function index()
    {
        return view('app.compensations.other-income-app-weekly.index');
    }
}
