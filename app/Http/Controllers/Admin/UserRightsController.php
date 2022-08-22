<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Mappers\Admin\UserRightsMapper;

class UserRightsController extends Controller
{
    //
    private $urights;

    public function __construct(UserRightsMapper $urights)
    {
        $this->urights = $urights;
    }

    public function index()
    {

        $rights = $this->urights->showAllRights();

        return view('app.admin.user-right.index',['rights' => $rights]);
    }

    public function showAllUsers(Request $request)
    {

        $filter = [
            'take' => $request->input('take'),
            'skip' => $request->input('skip'),
            'pageSize' => $request->input('pageSize'),
            'filter' => $request->input('filter'),
            'sort' => $request->input('sort'),
        ];

        $result = $this->urights->showAllUsers($filter);

        return response()->json($result);

    }

    // public function showUserRights($id)
    // {
    //     $result = $this->urights->getUsersRights($id);

    //     return response()->json($result);
    // }

    // public function createRights(Request $request)
    // {

    // }

    // public function destroyRights(Request $request)
    // {

    // }

    public function createRights(Request $request)
    {
    
        $data = [
            'user_id' => $request->user_id ,
            'sub_menu_id' => $request->rights_id ,
        ];

        $result = $this->urights->insertValid($data);

        return response()->json($result);

    }
    
    public function destroyRights(Request $request)
    {
        $data = [
            'user_id' => $request->user_id ,
            'sub_menu_id' => $request->rights_id ,
        ];

        $result = $this->urights->delete($data);


    }

    public function userRights(Request $request)
    {
        $result = $this->urights->getRights($request->user_id);
        return response()->json(['rights'=>$result]);
    }


}