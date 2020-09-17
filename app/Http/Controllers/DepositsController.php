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
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
