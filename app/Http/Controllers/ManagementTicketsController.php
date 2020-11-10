<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Requests\Ticket\TicketIdRequest;
use App\Http\Requests\Ticket\TicketPassRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Models\Ticket;
use App\Models\Game;
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
        $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
        $seller = ($request->seller > 0)?$request->seller:'all';
        
        return $this->RepositoryManagmentTickets->getSearchPaginated($criterion, $search, $status ,$date,$seller);
    }
    public function store(TicketStoreRequest $request){
            
                $this->RepositoryManagmentTickets->create($request->input());
                return response()->json(Answer('success','Ticket'));

        throw new GeneralException(__('El horario para crear tickets a terminado, intente mañana.'));
        
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryManagmentTickets->detail($request['id']));
    } 

    public function payment(Request $request)
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
