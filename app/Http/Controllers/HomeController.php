<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use App\Repositories\RepositoryHome;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositoryHome $RepositoryHome)
    {
        $this->middleware('auth');
        $this->RepositoryHome = $RepositoryHome;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {   
        
        if (!$request->ajax()) return view('home',['dm'=>accesUrl(Auth::user(),1)]);
        $criterion = ($request->criterion)?$request->criterion:'day';
        $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
        if(Auth::user()->type_user_id < 4 || Auth::user()->type_user_id > 4){
            return $this->RepositoryHome->data_index($date,$criterion);
        }else{
            return $this->RepositoryHome->data_indexC($date,$criterion);
        }
        
    
    }
}
