<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserIdRequest;
use App\Http\Requests\User\UserPassRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\User;
use App\Repositories\RepositoryUser;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
      /**
     * CompanyController constructor.
     *
     * @param RepositoryUser $RepositoryUser
     */
    public function __construct(RepositoryUser $RepositoryUser)
    {
        $this->RepositoryUser = $RepositoryUser;
    }


    public function index(UserRequest $request){

        if (!$request->ajax()) return view('user.index',['dm'=>accesUrl(Auth::user(),1)]);

        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryUser->getSearchPaginated($criterion, $search, $status);
    }
    public function store(UserStoreRequest $request){
        $this->RepositoryUser->create($request->input());
        return response()->json('ready');
    }

    public function update(UserUpdateRequest $request){

        $this->RepositoryUser->update($request['id'], $request->only(
            'name',
            'type',
            'last_name',
            'phone',
            'email',
        ));
        return response()->json('ready');
    }

    public function change_status(Request $request)
    {
        $this->RepositoryUser->updateStatus($request->id);
        return response()->json('exito');
    } 

    public function deleteOrResotore(Request $request)
    {    
        $roll = User::withTrashed()->find($request->id)->trashed();

            if($roll){
                User::withTrashed()->find($request->id)->restore();
            }else{
                User::find($request->id)->delete();
            }
        return response()->json('exito');
    }
}
