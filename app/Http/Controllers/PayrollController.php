<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserIdRequest;
use App\Http\Requests\User\UserPassRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\User;
use App\Repositories\RepositoryPayroll;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;


class PayrollController extends Controller
{
    /**
   * CompanyController constructor.
   *
   * @param RepositoryPayroll $RepositoryPayroll
   */
  public function __construct(RepositoryPayroll $RepositoryPayroll)
  {
      $this->RepositoryPayroll = $RepositoryPayroll;
  }


  public function index(UserRequest $request){

      if (!$request->ajax()) return view('reports.index',['dm'=>accesUrl(Auth::user(),1)]);
       
      $search = trim($request->search);
      $criterion = trim($request->criterion);
      $status = ($request->status)? $request->status : 1;
      $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
      $type = ($request->type)?$request->type:'day';

      return $this->RepositoryPayroll->getSearchPaginated($criterion, $search, $status ,$date,$type);
  }
  public function store(UserStoreRequest $request){
      $this->RepositoryPayroll->create($request->input());
      return response()->json('ready');
  }

  public function update(UserUpdateRequest $request){

      $this->RepositoryPayroll->update($request['id'], $request->only(
          'name',
          'type',
          'last_name',
          'percentage',
          'phone',
          'email',
      ));
      return response()->json('ready');
  }

  public function change_password(UserPassRequest $request)
  {   
      $this->RepositoryPayroll->update_password($request['id'], $request->only(
        'password',
      ));
  }

  public function change_status(Request $request)
  {
      $this->RepositoryPayroll->updateStatus($request->id);
      return response()->json('exito');
  } 

  public function deleteOrResotore(Request $request)
  {    
      $data = $this->RepositoryPayroll->deleteOrResotore($request['id']);
      
      return response()->json('exito');
  }
}
