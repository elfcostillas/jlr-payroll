<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use App\Mappers\Memo\AWOLMapper;
use Illuminate\Http\Request;

class AWOLMemoController extends Controller
{
    //
    private $mapper;

    public function __construct(AWOLMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.memo.awol-memo.index');
    }
}
