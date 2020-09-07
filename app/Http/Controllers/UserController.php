<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RequestUser;
use App\Http\Requests\RequestUserStore;
use App\Http\Requests\RequestUserUpdate;
use App\Http\Requests\RequestUserStatus;
use App\Http\Requests\RequestUserDelete;
use App\Models\User;
use App\Repositories\RepositoryUser;
use Carbon\Carbon; 


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


    public function index(Request $request){

        if (!$request->ajax()) return redirect('/');

        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryUser->getSearchPaginated($criterion, $search, $status);
    }


    public function store(Request $request){
        $this->RepositoryUser->create($request->input());
        return response()->json('ready');
    }

    public function update(Request $request){

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
