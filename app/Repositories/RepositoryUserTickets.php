<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Ticket;
use App\Models\User;
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
 * Class TicketRepository.
 */
class RepositoryUserTickets
{
    /**
     * TicketRepository constructor.
     *
     * @param  Ticket  $model
     */
    public function __construct(Ticket $model, TicketDetail $model_detail, Day_ticket $modelDAT)
    {
        $this->model = $model;
        $this->model_detail = $model_detail;
        $this->modelDAT = $modelDAT;
    }


          /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getSearchPaginated($criterion, $search, $status, $date)
    {
            
        $rg = $this->modelDAT->select( 'tickets.id as id',
                                        'tickets.phone as phone',
                                        'tickets.active as active',
                                        'tickets.winner as winner',
                                        'tickets.total as total',
                                        'tickets.deleted_at as deleted_at',
                                        'day_tickets.game_date as date')
                                ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id');

                        (strlen($criterion) > 0 &&  strlen($search) > 0) 
                            ? $rg->where($criterion, 'like', '%'. $search . '%')->where('tickets.user_id',Auth::user()->id)
                            : $rg->where('tickets.id','>',0)->where('tickets.user_id',Auth::user()->id);


                        if($date){
                             $rg->whereDate('day_tickets.game_date',$date);
                        }



                        if($status != 'all'){

                            switch ($status) {
                                case 1:
                                $rg->where('tickets.active',true);
                                break;
                                case 2:
                                $rg->where('tickets.active',false);
                                break;
                                case 3:
                                $rg->where('tickets.active',true)->where('tickets.winner',true);
                                break;
                                default:
                                $rg->where('tickets.active',true);
                            } 
                        }

        $Tickets = $rg->whereNull('tickets.deleted_at')->orderBy('tickets.id','desc')->paginate(10);
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
                'Games'=>Game::all(),
                'Days'=>Day::all(),
                'Date' =>($date)? Carbon::parse($date)->toDateString(): Carbon::now()->toDateString(),
                'Coins'=>Coin_purse::select('coins')->where('user_id',Auth::user()->id)->first(),
            ];
    }

    public function calculate_duration($date,$now,$time_end){
        $res = true;
        $dateS = Carbon::parse($date)->format('Y-m-d');
        $timeS = Carbon::parse($now);
        $dateTimeS = Carbon::parse($date)->addHour($timeS->hour)->addMinutes($timeS->minute)->addSeconds($timeS->second);
        
    
        $dateTimeE= Carbon::parse($time_end);
        $dateE = Carbon::parse($time_end)->format('Y-m-d');

        if($dateS < $dateE){
            $res = false;
        }else if($dateS == $dateE){
            $res = $dateTimeS->lt($dateTimeE);
        }
        
        return $res;

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
        
        $coins = Coin_purse::where('user_id',Auth::user()->id)->first();

        $tcoins = $coins['coins'] - $data['total'];
        if($tcoins > 0){
            return DB::transaction(function () use ($data) {
                $type = ($data['ticket_type'] > 0)? $data['ticket_type']: 0;
                $percentage = 0;
                $checked = 0;
                $no_pay_to = $data['pay_to'];

                for ($i=0; $i <= $type; $i++) {  
                
                    $Ticket = $this->model::create([
                        'user_id' => Auth::user()->id,
                        'ticket_type'=>$data['ticket_type'],
                        'phone' => Auth::user()->phone,
                        'total_seller' => $data['total']*$percentage,
                        'total_gain' => $data['total'] - ($data['total']*$percentage),
                        'total' => $data['total'],
                        'active'=>($no_pay_to >= $i)?true:false,
                    ]);
                    
            
                    if ($Ticket) {
                        $gameT = Game::where('id',$data['game']['id'])->first();
                        $time_end = Carbon::parse($gameT['time_end']);
                        $now = Carbon::now();
                        $totalDetail = 0;
                        foreach ($data['dataNewDays'] as $item){
                        
                            $date = ($item['day']['value'] > 0)
                                    ? Carbon::now()->startOfWeek()->addDays($item['day']['value'])->addWeeks($i)
                                    : Carbon::now()->startOfWeek()->addWeeks($i);

                            if(RepositoryUserTickets::calculate_duration($date,$now,$time_end) == true){
                                $dayT=Day_ticket::create([
                                        'ticket_id'=>$Ticket['id'],
                                        'day_id' => $item['day']['id'],
                                        'game_date'=>$date,
                                    ]);

                                if($dayT){
                                    $checked += 1;
                                        foreach ($data['dataNumbers'] as $detail){
                                            $totalDetail +=$detail['subtotal'];
                                            
                                        $tcd = $this->model_detail::create([
                                                'ticket_id'=>$Ticket['id'],
                                                'user_id' => Auth::user()->id,
                                                'game_id'=>$data['game']['id'],
                                                'figures'=>$detail['figures'],
                                                'date_ticket'=>$date,
                                                'game_number' => $detail['number'],
                                                'bet' => $detail['subtotal'],
                                                'bet_seller'=>$detail['subtotal']*$percentage,
                                                'bet_gain'=>$detail['subtotal']-($detail['subtotal']*$percentage),
                                                'prize'=>0,
                                                'active'=>($no_pay_to >= $i)?true:false,
                                            ]);
                                            if(!$tcd){
                                                throw new GeneralException(__('detalle error.'));
                                                break; 
                                            }
                                        }

                                }else{
                                    throw new GeneralException(__('dia error.'));
                                                break; 
                                }
                            }
                        }
                      

                      
                            $totalDays = $totalDetail ;
                            $this->model->find($Ticket['id'])->update([
                                                                        'total_seller' => $data['total']*$percentage,
                                                                        'total_gain' => $data['total'] - ($data['total']*$percentage),
                                                                        'total'=>$totalDays]);
                     
                    }else{
                        throw new GeneralException(__('Error en crear ticket.'));
                        break;
                    }
            
                }
                if($checked == 0){
                    throw new GeneralException(__('Error en crear ticket.'));
                }
                return 'exito';
               
                
                    
            });
        }
        throw new GeneralException(__('Tu saldo actual es de $'.$coins['coins'].'pesos y no es suficiente.'));
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
                $days = Day_ticket::select('day.name as name', 'day_tickets.game_date as date')
                                    ->join('days as day','day.id', "=", 'day_tickets.day_id')
                                    ->where('day_tickets.ticket_id',$Ticket_id)
                                    ->get();

                return ['ticket' => $Ticket, 'ticketDetail'=>$detail,'client'=>$client,'days'=>$days];
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
     
    public function updateStatus($Ticket_id): Ticket
    {
        
        $Ticket = $this->model->find($Ticket_id);
        $coins = Coin_purse::where('user_id',Auth::user()->id)->first();
           
            switch ($Ticket->active) {
                case 0:
                    $Ticket->active = 1;
                    $tcoins = $coins['coins'] - $Ticket['total'];
                break;
                case 1:
                    $Ticket->active = 0;
                    $tcoins = $coins['coins'] + $Ticket['total'];
                break;
            }
                if($tcoins > 0){
                    if($coins->update(['coins'=>$tcoins])){
                        if ($Ticket->save()) {
                            return $Ticket;
                        }
                    }
                }
    
        throw new GeneralException(__('Tu saldo actual es de $'.$coins['coins'].'pesos y no es suficiente.'));
    }

    public function deleteOrResotore($Ticket_id)
    {   
        
        return DB::transaction(function () use ($Ticket_id) {
        
            $ticket = Ticket::find($Ticket_id);

            $coins =Coin_purse::where('user_id',Auth::user()->id)->first();

            if($ticket->active == 1){
                $coins->coins += $ticket['total'];
            }
            
            if ($coins->save()) {
             
                    if(Ticket::find($ticket['id'])->delete()){
                        $Ticket_detail=TicketDetail::where('ticket_id',$Ticket_id)->delete();
                        return $coins;
                    }
                throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
            }
            throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
        });
    
        
    }

}
