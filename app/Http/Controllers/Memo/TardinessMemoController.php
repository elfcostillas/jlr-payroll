<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\Memo\TardinessMemoMapper;

class TardinessMemoController extends Controller
{
    //
    private $mapper;

    public function __construct(TardinessMemoMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function index()
    {
        return view('app.memo.tardiness-memo.index');
    }

    public function list(Request $request)
    {
        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
            'search' => $request->input('search'),
        ];

        $result = $this->mapper->list($filter);

        return response()->json($result);
    }

    public function readMemo(Request $request)
    {
        $result = $this->mapper->readMemo($request->id);

        return response()->json($result);
    }
}
