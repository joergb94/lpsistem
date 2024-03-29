<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserPassRequest;
use App\Http\Requests\User\UserProfileRequest;
use App\Repositories\RepositoryProfile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
       /**
     * CompanyController constructor.
     *
     * @param RepositoryProfile $RepositoryProfile
     */
    public function __construct(RepositoryProfile $RepositoryProfile)
    {
        $this->RepositoryProfile = $RepositoryProfile;
    }


    public function index(Request $request){
        
        if (!$request->ajax()) return view('user.index',['dm'=>accesUrl(Auth::user(),1)]);
        return $this->RepositoryProfile->get_info(Auth::user()->id);
    }
    
    public function update(UserProfileRequest $request)
    {
        $this->RepositoryProfile->update($request['id'], $request->only(
            'name',
            'last_name',
            'phone',
            'email',
        ));
        return response()->json('ready');
    }

    public function change_password(UserPassRequest $request)
    {   
        dd($request->input());
        $this->RepositoryProfile->update_password($request['id'], $request->only(
          'password',
        ));
    }

    
}
