<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserTicketsController extends Controller
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
        return response()->json(Answer('success','Ticket'));
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryManagmentTickets->detail($request['id']));
    } 

    public function change_status(Request $request)
    {
        $this->RepositoryManagmentTickets->updateStatus($request->id);
        return response()->json(Answer('success','Ticket'));
    } 

    public function deleteOrResotore(Request $request)
    {    
        Ticket::find($request->id)->delete();
        return response()->json(Answer('success','Ticket'));
    }
}
