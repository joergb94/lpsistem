<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Requests\Ticket\TicketIdRequest;
use App\Http\Requests\Ticket\TicketPassRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Models\Ticket;
use App\Repositories\RepositoryGameWinner;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;

class GameWinnerController extends Controller
{
      /**
     * CompanyController constructor.
     *
     * @param RepositoryGameWinner $RepositoryGameWinner
     */
    public function __construct(RepositoryGameWinner $RepositoryGameWinner)
    {
        $this->RepositoryGameWinner = $RepositoryGameWinner;
    }


    public function index(TicketRequest $request){
        
        if (!$request->ajax()) return view('winners.index',['dm'=>accesUrl(Auth::user(),3)]);
       
        $search = trim($request->search);
        $criterion = 'ticket_details.game_number';
        $status = ($request->status)? $request->status : 1;
        $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
        
        return $this->RepositoryGameWinner->getSearchPaginated($criterion, $search, $status ,$date);
    }
    public function store(TicketStoreRequest $request){
        
        $this->RepositoryGameWinner->create($request->input());
        return response()->json(Answer('success','Ticket'));
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryGameWinner->detail($request['id']));
    } 

    public function payment(Request $request)
    {
        $this->RepositoryGameWinner->updateStatus($request->id);
        return response()->json(Answer('success','Ticket'));
    } 

    public function deleteOrResotore(Request $request)
    {    
        Ticket::find($request->id)->delete();
        return response()->json(Answer('success','Ticket'));
    }
}
