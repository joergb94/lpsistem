<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Requests\Ticket\TicketIdRequest;
use App\Http\Requests\Ticket\TicketPassRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Models\Ticket;
use App\Models\Game_schedule;
use App\Models\Game_schedules_detail;
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
        $criterion = 'tickets.phone';
        $status = ($request->status)? $request->status : 1;
        $date =($request->date)? Carbon::parse($request['date']): Carbon::now();
        $game = ($request->game)?$request->game : 0;
        $game_schedule = ($request->game_schedule)?$request->game_schedule : 0;
        $game_detail = ($request->game_detail > 0)?$request->game_detail : '';
        $figures =($request->figure > 0)?$request->figure: 0;
       
        
        return $this->RepositoryGameWinner->getSearchPaginated($criterion, $search, $status , $date , $game , $game_schedule , $game_detail,$figures);
    }
    public function store(TicketStoreRequest $request){
        
        $this->RepositoryGameWinner->create($request->input());
        return response()->json(Answer('success','Ticket'));
    }

    public function detail(Request $request)
    {
        return response()->json($this->RepositoryGameWinner->detail($request['id']));
    } 

    public function win(Request $request)
    {
        $this->RepositoryGameWinner->updateStatus($request->id);
        return response()->json(Answer('success','Ticket'));
    } 

    public function deleteOrResotore(Request $request)
    {    
        Ticket::find($request->id)->delete();
        return response()->json(Answer('success','Ticket'));
    }

    public function detail_game(Request $request)
    {
        return response()->json(Game_schedules_detail::where('game_schedule_id',$request['id'])->get());
    } 

    public function game(Request $request)
    {
        return response()->json(Game_schedule::with('games')->where('date',$request['date'])->get());
    } 
}
