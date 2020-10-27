<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Game_schedule;
use App\Models\Game_schedules_detail;
use App\Models\Game;
use App\Models\Day;
use App\Models\Day_ticket;
use App\Models\Coin_purse;
use App\Models\TicketDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 

/**
 * Class ProviderRepository.
 */
class RepositoryHome
{
    /**
     * ProviderRepository constructor.
     *
     * @param  Ticket  $model
     */
    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function data_tickets($date,$type,$pay)
    {

          $Ticket = Day_ticket::select(DB::raw('SUM(ticket_details.bet) as total_bet'))
                                ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id')
                                ->join('ticket_details','ticket_details.ticket_id', "=", 'day_tickets.ticket_id');

                            if(Auth::user()->type_user_id < 3 ){
                                    $Ticket->where('tickets.id','>=',0);
                    
                            }else if(Auth::user()->type_user_id == 3){
                    
                                    $Ticket->where('tickets.seller_id',Auth::user()->id); 
                                    
                            }else if(Auth::user()->type_user_id == 4){
                    
                                    $Ticket->where('tickets.charged_id',Auth::user()->id);
                            }else{
                                    
                                    $Ticket->where('tickets.id','>=',0);
                            }

                        switch ($type) {
                                case 'day':
                                    $Ticket->whereDate('day_tickets.game_date', $date);
                                    break;
                                case 'week':
                                    $Ticket->whereDate('day_tickets.game_date','>=',$date->startOfWeek())->whereDate('day_tickets.game_date','<=',$date->endOfWeek());
                                    break;
                                    
                                case 'month':
                                    $Ticket->whereMonth('day_tickets.game_date', $date->month)->whereYear('day_tickets.game_date', $date->year);
                                    break;
                                default:
                                    $Ticket->whereDate('day_tickets.game_date', $date);
                                    break;
                            }
                            
                            
                         $data=$Ticket->where('ticket_details.active',$pay)->first();
        return $data;
    }
    public function data_winners($date,$type)
    {

        $Ticket = Day_ticket::select(DB::raw('SUM(ticket_details.prize) as total_prize'))
                                ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id')
                                ->join('ticket_details','ticket_details.ticket_id', "=", 'day_tickets.ticket_id');

                        if(Auth::user()->type_user_id < 3 || Auth::user()->type_user_id > 4){
                                $Ticket->where('tickets.id','>=',0);

                        }else if(Auth::user()->type_user_id == 3){

                                $Ticket->where('tickets.seller_id',Auth::user()->id); 
                                
                        }else{
                                $Ticket->where('tickets.user_id',Auth::user()->id);
                        }

                        switch ($type) {
                                case 'day':
                                    $Ticket->whereDate('day_tickets.game_date', $date);
                                    break;
                                case 'week':
                                    $Ticket->whereDate('day_tickets.game_date','>=',$date->startOfWeek())->whereDate('day_tickets.game_date','<=',$date->endOfWeek());
                                    break;
                                    
                                case 'month':
                                    $Ticket->whereMonth('day_tickets.game_date', $date->month)->whereYear('day_tickets.game_date', $date->year);
                                    break;
                                default:
                                    $Ticket->whereDate('day_tickets.game_date', $date);
                                    break;
                            }
                            
                            
                         $data=$Ticket->where('ticket_details.active',1)->where('ticket_details.winner',1)->first();
        return $data;
    }

    public function byTicketsPayOff($date,$type,$pay)
    {
        $Ticket = Day_ticket::select(   'tickets.id as id',
                                        'tickets.phone as phone',
                                        'tickets.active as active',
                                        'tickets.winner as winner',
                                        'tickets.total as total',
                                        'tickets.deleted_at as deleted_at',
                                        'day_tickets.game_date as date')
                                ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id');

        if(Auth::user()->type_user_id < 3 ){
                $Ticket->where('tickets.id','>=',0);

        }else if(Auth::user()->type_user_id == 3){

                $Ticket->where('tickets.seller_id',Auth::user()->id); 
                
        }else if(Auth::user()->type_user_id == 4){

                $Ticket->where('tickets.charged_id',Auth::user()->id);
        }else{
                
                $Ticket->where('tickets.id','>=',0);
        }

        switch ($type) {
            case 'day':
                $Ticket->whereDate('day_tickets.game_date', $date);
                break;
            case 'week':
                $Ticket->whereDate('day_tickets.game_date','>=',$date->startOfWeek())->whereDate('day_tickets.game_date','<=',$date->endOfWeek());
                break;
                
            case 'month':
                $Ticket->whereMonth('day_tickets.game_date', $date->month)->whereYear('day_tickets.game_date', $date->year);
                break;
            default:
                $Ticket->whereDate('day_tickets.game_date', $date);
                break;
        }
        
        $data=$Ticket->where('active',$pay)->count();
        return $data;
    }

    public function table_byTicket($date,$type)
    {
        $Ticket = Day_ticket::select(   'tickets.id as id',
                                        'tickets.phone as phone',
                                        'tickets.active as active',
                                        'tickets.winner as winner',
                                        'tickets.total as total',
                                        'tickets.deleted_at as deleted_at',
                                        'day_tickets.game_date as date')
                                ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id');

        if(Auth::user()->type_user_id < 3 || Auth::user()->type_user_id > 4){
                $Ticket->where('tickets.id','>=',0);

        }else if(Auth::user()->type_user_id == 3){

                $Ticket->where('tickets.seller_id',Auth::user()->id); 
                
        }else{
                $Ticket->where('tickets.user_id',Auth::user()->id);
        }

        if(Auth::user()->type_user_id < 3 || Auth::user()->type_user_id > 4){
            switch ($type) {
                case 'day':
                    $Ticket->whereDate('day_tickets.game_date', $date);
                    break;
                case 'week':
                    $Ticket->whereDate('day_tickets.game_date','>=',$date->startOfWeek())->whereDate('day_tickets.game_date','<=',$date->endOfWeek());
                    break;
                    
                case 'month':
                    $Ticket->whereMonth('day_tickets.game_date', $date->month)->whereYear('day_tickets.game_date', $date->year);
                    break;
                default:
                    $Ticket->whereDate('day_tickets.game_date', $date);
                    break;
            }
        }
        if(Auth::user()->type_user_id < 4 || Auth::user()->type_user_id > 4){
            $data = $Ticket->whereNull('tickets.deleted_at')->where('active',true)->paginate(10);

          }else{
            $data = $Ticket->whereNull('tickets.deleted_at')->where('winner',true)->paginate(10);
            }
       

        return [
            'pagination' => [
                'total'        => $data->total(),
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'last_page'    => $data->lastPage(),
                'from'         => $data->firstItem(),
                'to'           => $data->lastItem(),
            ],
            'Tickets' => $data
        ];
    }

          /**
     * @param int    $paged
     * @param string $TicketBy
     * @param string $sort
     *
     * @return mixed
     */
    public function data_index($date_filter,$type_filter)
    {
        $date = ($date_filter)?  Carbon::parse($date_filter) : Carbon::now();
        $type = ($type_filter)?  $type_filter : 'day';
        $home = [
                    "tickets_pay_off"=>RepositoryHome::byTicketsPayOff($date,$type,true),
                    "tickets_not_pay_off"=>RepositoryHome::byTicketsPayOff($date,$type,false),
                    "prizes"=>RepositoryHome::data_winners($date,$type),
                    "not_pay"=>RepositoryHome::data_tickets($date,$type,false),
                    "incomes"=>RepositoryHome::data_tickets($date,$type,true),
                    "TableTickets"=>RepositoryHome::table_byTicket($date,$type),
                    "date"=>$date->format('Y-m-d'),
                    "type"=>Auth::user()->type_user_id
                ];
            return $home;
    }

    /**
     * section for client dashboard 
     */

    public function data_games($date){

                return Game_schedule::where('id','>',0)->with('games')->with('game_schedule_details')
                ->whereDate('date','>=',$date->startOfWeek())->whereDate('date','<=',$date->endOfWeek())->get();
     }


  
   public function data_indexC($date_filter,$type_filter)
   {
       $date = ($date_filter)?  Carbon::parse($date_filter) : Carbon::now();
       $type = ($type_filter)?  $type_filter : 'day';
       $home = [
                   "games"=>RepositoryHome::data_games($date),
                   "TableTickets"=>RepositoryHome::table_byTicket($date,$type),
                   "date"=>$date->format('Y-m-d')
               ];
           return $home;
   }

   

  
}
