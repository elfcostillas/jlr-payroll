<?php

namespace App\Http\Controllers\Compentsations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FixCompensationController extends Controller
{
    //
    public function __construct()
    {

    }

    public function index()
    {
        return view('app.compensations.fixed-compensation.index');
    }
}
