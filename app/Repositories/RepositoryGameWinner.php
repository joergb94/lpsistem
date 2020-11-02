<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Game;
use App\Models\Game_detail;
use App\Models\Game_schedule;
use App\Models\Game_schedules_detail;
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
 * Class TicketRepository.
 */
class RepositoryGameWinner
{
    /**
     * TicketRepository constructor.
     *
     * @param  Ticket  $model
     */
    public function __construct(Ticket $model, TicketDetail $model_detail,Day_ticket $modelDAT)
    {
        $this->model = $model;
        $this->model_detail = $model_detail;
        $this->modelDAT = $modelDAT;
    }

    public function figures($game_detail,$figures){

                        $number = '';
                        $number2 ='';
                                        
                        $numberArray =str_split($game_detail);
                        $prereverse =array_reverse($numberArray);
                                    
                        $valNumber = ($figures <= count($numberArray))?$figures:count($numberArray);
                                        
                        for ($i=0; $i < $valNumber; $i++) { 
                                            
                                $number2 =$number2.''.$prereverse[$i];
                            }
                                            
                        $numberArray2 =str_split($number2);
                        $reverse = array_reverse($numberArray2);
                
                        for ($i=0; $i < $valNumber; $i++) { 
                                            
                                $number =$number.''.$reverse[$i];
                            }

            return $number;

    }


          /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getSearchPaginated($criterion, $search, $status, $date, $game , $game_schedule , $game_detail,$figures)
    {      
        $rg = $this->modelDAT->select( 'ticket_details.id as id',
                                        'tickets.id as ticket_id',
                                        'ticket_details.winner as winner',
                                        'tickets.phone as phone',
                                        'ticket_details.game_number as number',
                                        'ticket_details.bet as bet',
                                        'ticket_details.prize as prize',
                                        'tickets.deleted_at as deleted_at',
                                        'ticket_details.date_ticket as date')
                            ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id')
                            ->join('ticket_details','ticket_details.date_ticket', "=", 'day_tickets.game_date');

            (strlen($criterion) > 0 &&  strlen($search) > 0) 
                     ? $rg->where('tickets.phone', 'like', '%'. $search . '%')->whereDate('day_tickets.game_date',$date)
                     : $rg->where('tickets.id','>',0)->whereDate('day_tickets.game_date',$date);
                
                     $rg->where('tickets.active',true)->where('ticket_details.active',true);

                  


                    if($game > 0  && strlen($game_detail) > 0 && $figures > 0){   
                        
                            $rg->where('ticket_details.game_id',$game)
                                ->where('ticket_details.game_number',RepositoryGameWinner::figures($game_detail,$figures));
                           
                    }else if(strlen($game_detail) > 0){

                        $figuresArray = [];
                        $cycles =strlen($game_detail);
                        
                        for ($i=0; $i <= $cycles ; $i++) { 
                            array_push($figuresArray,RepositoryGameWinner::figures($game_detail,$i));
                        }
                        
                        $rg->where('ticket_details.game_id',$game)
                            ->whereIn('ticket_details.game_number',$figuresArray);
                            
                    }
                    
                    if($game > 0){
                        $rg->where('ticket_details.game_id',$game);
                    }
               
                
                
                $Tickets = $rg->whereNull('tickets.deleted_at')->orderBy('tickets.id', 'desc')->paginate(10);
               
        return [
                'pagination' => [
                    'total'        => $Tickets->total(),
                    'current_page' => $Tickets->currentPage(),
                    'per_page'     => $Tickets->perPage(),
                    'last_page'    => $Tickets->lastPage(),
                    'from'         => $Tickets->firstItem(),
                    'to'           => $Tickets->lastItem(),
                ],
                'Tickets' => $Tickets,
                'Days'=>Day::all(),
                'Date' =>($date)? Carbon::parse($date)->toDateString(): Carbon::now()->toDateString(),
            ];
    }

  
    /**
     * @param array $data
     *
     * @throws \Exception
     * @throws \Throwable
     * @return Ticket
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $Client = (User::where('phone',$data['phone'])->count() == 0)
            ? User::create([
                'type_user_id'=>4,
                'name' => $data['phone'],
                'phone' => $data['phone'],
                'email' => $data['phone'].'@lp.com',
                'password' =>Hash::make($data['phone']),
            ])
            : User::where('phone',$data['phone'])
                    ->where('type_user_id',4)
                    ->first();

            if ($Client) {
                if(Coin_purse::create(['user_id' => $Client->id,
                                        'coins' => 0]))
                {

                $type = ($data['ticket_type'] > 1)? 4 : 0;

                for ($i=0; $i <= $type; $i++) { 
                   
                    $Ticket = $this->model::create([
                        'seller_id'=>Auth::user()->id,
                        'user_id' => $Client['id'],
                        'ticket_type'=>$data['ticket_type'],
                        'phone' => $data['phone'],
                        'total' => $data['total'],
                        'active'=>false,
                    ]);
                    
            
                    if ($Ticket) {
                        $now = Carbon::now();
                        $totalDetail = 0;
                       
                        foreach ($data['dataNumbers'] as $detail){
                            $totalDetail +=$detail['subtotal'];
                            
                            $this->model_detail::create([
                                'ticket_id'=>$Ticket['id'],
                                'user_id' => $Client['id'],
                                'game_id'=>$data['game']['id'],
                                'game_number' => $detail['number'],
                                'bet' => $detail['subtotal'],
                                'active'=>false,
                            ]);
                        }

                        foreach ($data['dataNewDays'] as $item){
                          
                            $date = ($item['day']['value'] > 0)
                                    ? Carbon::now()->startOfWeek()->addDays($item['day']['value'])->addWeeks($i)
                                    : Carbon::now()->startOfWeek()->addWeeks($i);
                            if($date >= $now){

                                Day_ticket::create([
                                    'ticket_id'=>$Ticket['id'],
                                    'day_id' => $item['day']['id'],
                                    'game_date'=>$date,
                                ]);

                            }
                        }

                        $noDays = Day_ticket::where('ticket_id',$Ticket['id'])->count();
                        $totalDays = $totalDetail * $noDays;
                        $this->model->find($Ticket['id'])->update(['total'=>$totalDays]);
                    }
               
                }
                    
                        
                return 'exito';
             }
            }
            throw new GeneralException(__('El numero que esta Ingresando no es valido.'));
        });
    }

    /**
     * @param Ticket  $Ticket
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return Ticket
     */
    public function detail($Ticket_id)
    {
        
        
        
        return DB::transaction(function () use ($Ticket_id) {
            if ($Ticket = $this->model->find($Ticket_id)) {
                
                $detail = $this->model_detail->where('ticket_id',$Ticket['id'])->with('games')->get();
                $client = User::find($Ticket['user_id']);
                $game =Game::where('id',$detail[0]['game_id'])->first();
                $days = Day_ticket::select('day.name as name', 'day_tickets.game_date as date')
                                    ->join('days as day','day.id', "=", 'day_tickets.day_id')
                                    ->where('day_tickets.ticket_id',$Ticket_id)
                                    ->get();

                return ['ticket' => $Ticket, 'ticketDetail'=>$detail,'client'=>$client,'days'=>$days,'gamet'=>$game];
            }

            throw new GeneralException(__('There was an error on show the Ticket.'));
        });
    }


    
    /*
     * @param Ticket $Ticket
     * @param      $status
     *
     * @throws GeneralException
     * @return Ticket
     */
     
    public function updateStatus($TicketDetailData)
    {
    
        return DB::transaction(function () use ($TicketDetailData) {
            $TicketDetail = $this->model_detail->find($TicketDetailData['id']);
            $prize = 0;
            switch ($TicketDetail->winner) {
                case 0:
                    $TicketDetail->winner = true;
                        
                    $win =$TicketDetail['game_id'] < 5
                                    ? Game_detail::where('game_id',$TicketDetail['game_id'])
                                        ->where('figures',$TicketDetail['figures'])
                                        ->first()
                                    : Game_detail::where('game_id',$TicketDetail['game_id'])
                                        ->where('figures',$TicketDetailData['type_game'])
                                        ->first();

                    switch ($win['game_id']) {
                            case 1:
                                $prize =$TicketDetail['bet']*$win['prize'];
                            break;
                            case 2:
                                $prize =$TicketDetail['bet']*$win['prize'];
                            break;
                            case 3:
                                $prize = $TicketDetail['bet']*$win['prize'];
                            break;
                            case 3:
                                $prize = $TicketDetail['bet']*$win['prize'];
                            break;
                            case 4:
                                $prize = $TicketDetail['bet']*$win['prize'];
                            break;
                            case 5:
                                $prize = $TicketDetail['bet']*$win['prize'];
                            break;
                    }
                   

                    $TicketDetail->prize = $prize;
                break;
                case 1:
                    $TicketDetail->winner = false;
                    $TicketDetail->prize = $prize; 
                break;
            }

            if ($TicketDetail->save()) {

                $Ticket = $this->model->find($TicketDetail->ticket_id);

                $win =  $this->model_detail
                        ->where('ticket_id',$Ticket->id)
                        ->where('winner',true)
                        ->exists();

                if ($Ticket->update([
                        'winner' => $win,
                        
                    ])) {
                        return $TicketDetail;
                    }
                    throw new GeneralException(__('Error changing status of Ticket.'));
            }

            throw new GeneralException(__('Error changing status of Ticket.'));
        });
    }

    public function deleteOrResotore($Ticket_id)
    {    
        $Bval = Ticket::withTrashed()->find($Ticket_id)->trashed();

            if($Bval){
                $Ticket = Ticket::withTrashed()->find($Ticket_id)->restore();
                $b=4;
            }else{
                $Ticket = Ticket::find($Ticket_id)->delete();
                $b=3;
            }

            if ($b) {
                return $b;
            }
    
            throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
    }



}
