<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Deposit\DepositRequest;
use App\Http\Requests\Deposit\DepositIdRequest;
use App\Http\Requests\Deposit\DepositPassRequest;
use App\Http\Requests\Deposit\DepositUpdateRequest;
use App\Http\Requests\Deposit\DepositStoreRequest;
use App\Models\Deposit;
use App\Repositories\RepositoryDeposit;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;

class DepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RepositoryDeposit $RepositoryDeposit)
    {
        $this->RepositoryDeposit = $RepositoryDeposit;
    }

    public function index(DepositRequest $request)
    {
        if (!$request->ajax()) return view('deposits.index',['dm'=>accesUrl(Auth::user(),1)]);
        
        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryDeposit->getSearchPaginated($criterion, $search, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepositStoreRequest $request)
    {   
        echo 'hola';
        
        $base64_str = substr($request->imageDep, strpos($request->imageDep, ",")+1); 

        $image = base64_decode($base64_str); 
        $png_url = "Deposit-".time()."-".Auth::id().".png"; 
        $path = public_path('images/deposits/' . $png_url); 

        file_put_contents($path, $image);
        $data = array(
            'bank' =>$request->bank ,
            'numDep' => $request->numDep,
            'imageDep' => $png_url,
            'amount' => $request->amount,
            'description' => $request->description,
            'id_user' => Auth::id(),
            'status' => 1,
        );

        $this->RepositoryDeposit->create($data);
        return response()->json(Answer('success','Ticket'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status(Request $request)
    {   
        //dd($request->input());
        $this->RepositoryDeposit->updateStatus($request);
        return response()->json('exito');
    } 

    public function deleteOrResotore(Request $request)
    {    
        $roll = Deposit::withTrashed()->find($request->id)->trashed();

            if($roll){
                Deposit::withTrashed()->find($request->id)->restore();
            }else{
                Deposit::find($request->id)->delete();
            }
        return response()->json('exito');
    }
}
