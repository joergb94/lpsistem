<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Requests\Ticket\TicketIdRequest;
use App\Http\Requests\Ticket\TicketPassRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Models\Ticket;
use App\Repositories\RepositoryUserTickets;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;


class UserTicketsController extends Controller
{
      /**
     * CompanyController constructor.
     *
     * @param RepositoryUserTickets $RepositoryUserTickets
     */
    public function __construct(RepositoryUserTickets $RepositoryUserTickets)
    {
        $this->RepositoryUserTickets = $RepositoryUserTickets;
    }


    public function index(TicketRequest $request){
        
        if (!$request->ajax()) return view('tickets.index',['dm'=>accesUrl(Auth::user(),3)]);
        
        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryUserTickets->getSearchPaginated($criterion, $search, $status);
    }
    public function store(Request $request){
        
        $this->RepositoryUserTickets->create($request->input());
        return response()->json(Answer('success','Ticket'));
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryUserTickets->detail($request['id']));
    } 

    public function change_status(Request $request)
    {
        $this->RepositoryUserTickets->updateStatus($request->id);
        return response()->json(Answer('success','Ticket'));
    } 

    public function deleteOrResotore(Request $request)
    {    
        $this->RepositoryUserTickets->deleteOrResotore($request->id);
        return response()->json(Answer('success','Ticket'));
    }
}
