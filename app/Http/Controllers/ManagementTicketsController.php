<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Requests\Ticket\TicketIdRequest;
use App\Http\Requests\Ticket\TicketPassRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Models\Ticket;
use App\Repositories\RepositoryManagmentTickets;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;


class ManagementTicketsController extends Controller
{
      /**
     * CompanyController constructor.
     *
     * @param RepositoryManagmentTickets $RepositoryManagmentTickets
     */
    public function __construct(RepositoryManagmentTickets $RepositoryManagmentTickets)
    {
        $this->RepositoryManagmentTickets = $RepositoryManagmentTickets;
    }


    public function index(TicketRequest $request){
        
        if (!$request->ajax()) return view('tickets.index',['dm'=>accesUrl(Auth::user(),3)]);
        
        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryManagmentTickets->getSearchPaginated($criterion, $search, $status);
    }
    public function store(TicketStoreRequest $request){
        
        $this->RepositoryManagmentTickets->create($request->input());
        return response()->json('ready');
    }

    public function change_status(Request $request)
    {
        $this->RepositoryManagmentTickets->updateStatus($request->id);
        return response()->json('exito');
    } 

    public function deleteOrResotore(Request $request)
    {    
        $roll = Ticket::withTrashed()->find($request->id)->trashed();

            if($roll){
                Ticket::withTrashed()->find($request->id)->restore();
            }else{
                Ticket::find($request->id)->delete();
            }
        return response()->json('exito');
    }
}
