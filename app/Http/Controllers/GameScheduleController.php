<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GameSchedule\ScheduleRequest;
use App\Http\Requests\GameSchedule\ScheduleUpdateRequest;
use App\Http\Requests\GameSchedule\ScheduleStoreRequest;
use App\Models\Game_schedule;
use App\Repositories\RepositoryGameSchedule;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;

class GameScheduleController extends Controller
{
       /**
     * CompanyController constructor.
     *
     * @param RepositoryGameSchedule $RepositoryGameSchedule
     */
    public function __construct(RepositoryGameSchedule $RepositoryGameSchedule)
    {
        $this->RepositoryGameSchedule = $RepositoryGameSchedule;
    }


    public function index(ScheduleRequest $request){

        if (!$request->ajax()) return view('Schedules.index',['dm'=>accesUrl(Auth::user(),1)]);
        
        $search = trim($request->search);
        $criterion = trim($request->criterion);
        $status = ($request->status)? $request->status : 1;
  
        return $this->RepositoryGameSchedule->getSearchPaginated($criterion, $search, $status);
    }
    public function store(ScheduleStoreRequest $request){
        $this->RepositoryGameSchedule->create($request->input());
        return response()->json('ready');
    }

    public function update(ScheduleUpdateRequest $request){

        $this->RepositoryGameSchedule->update($request['id'], $request->only(
            'number_win',
            'number_win2',
            'game_id',
            'date'
        ));
        return response()->json('ready');
    }

    public function change_status(ScheduleRequest $request)
    {
        $this->RepositoryGameSchedule->updateStatus($request->id);
        return response()->json('exito');
    } 

    public function deleteOrResotore(ScheduleRequest $request)
    {    
        $data = $this->RepositoryGameSchedule->deleteOrResotore($request['id']);
        
        return response()->json('exito');
    }
}
