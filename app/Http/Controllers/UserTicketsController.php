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
        
        if (!$request->ajax()) return view('tickets.index',);
        
        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 'all';
        $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
  
        return $this->RepositoryUserTickets->getSearchPaginated($criterion, $search, $status, $date);
    }
    public function store(Request $request){
        if(Game::whereTime('time_end', '>=',Carbon::now())
        ->exists())
        {
            $this->RepositoryUserTickets->create($request->input());
            return response()->json(Answer('success','Ticket'));
        }
        throw new GeneralException(__('El horario para crear tickets a terminado, intente mañana.'));
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryUserTickets->detail($request['id']));
    } 

    public function payment(Request $request)
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
