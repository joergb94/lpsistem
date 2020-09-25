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
    public function __construct(Ticket $model, TicketDetail $model_detail)
    {
        $this->model = $model;
        $this->model_detail = $model_detail;
    }


          /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getSearchPaginated($criterion, $search, $status)
    {
            
        $rg = (strlen($criterion) > 0 &&  strlen($search) > 0) 
                     ? $this->model->where($criterion, 'like', '%'. $search . '%')->where('user_id',Auth::user()->id)
                     : $this->model->where('id','>',0)->where('user_id',Auth::user()->id);
                
                if($status != 'all'){

                        switch ($status) {
                            case 1:
                                $rg->active();
                            break;
                            case 2:
                                $rg->active(false);
                            break;
                            case 'D':
                                $rg->onlyTrashed();
                            break;
                            default:
                                $rg->active();
                        } 
                }
                
                $Tickets = $rg->orderBy('id', 'desc')->paginate(10);
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
        
        $coins = Coin_purse::where('user_id',Auth::user()->id)->first();

        $tcoins = $coins['coins'] - $data['total'];
        if($tcoins > 0){
            return DB::transaction(function () use ($data) {
                
                    $Ticket = $this->model::create([
                            'user_id' => Auth::user()->id,
                            'ticket_type'=>$data['ticket_type'],
                            'phone' => Auth::user()->phone,
                            'total' => $data['total'],
                            'active'=>true,
                        ]);

                    if($Ticket)
                    {
                        $coins =Coin_purse::where('user_id',Auth::user()->id)->first();
              
                        //operations 
                        $totalC = $coins['coins'] - $data['total'];
                        $ct = ($totalC > 0)? $totalC : 0;
                       
                        if ($coins->update(['coins'=>$ct])) 
                        {
                            $now = Carbon::now();
                                foreach ($data['dataNumbers'] as $detail){
                                    $this->model_detail::create([
                                        'ticket_id'=>$Ticket['id'],
                                        'user_id' => Auth::user()->id,
                                        'game_id'=>$data['game']['id'],
                                        'game_number' => $detail['number'],
                                        'bet' => $detail['subtotal'],
                                        'active'=>true,
                                    ]);
                                }

                                foreach ($data['dataNewDays'] as $item){
                                    $date = ($item['day']['value'] == 0)
                                            ? Carbon::now()->startOfWeek()->addDays($item['day']['value'])->addWeeks(0)
                                            : Carbon::now()->startOfWeek()->addWeeks(0);

                                    if($date >= $now){

                                        Day_ticket::create([
                                            'ticket_id'=>$Ticket['id'],
                                            'day_id' => $item['day']['id'],
                                            'game_date'=>$date,
                                        ]);

                                    }
                                   
                                }
                                
                                if($this->model_detail
                                    ->where('ticket_id',$Ticket['id'])
                                    ->count() > 0)
                                {
                                    return $Ticket;
                                }
                            }
                        throw new GeneralException(__('No se pudo crear el ticket intente nuevamente.'));   
                        }
                    throw new GeneralException(__('No se pudo crear el ticket intente nuevamente.'));
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
                
                $detail = $this->model_detail->where('ticket_id',$Ticket['id'])->get();
                $client = User::find($Ticket['user_id']);
                $days = Day_ticket::select('day.name as name', 'day_tickets.game_date as date')
                                    ->join('days as day','day.id', "=", 'day_tickets.day_id')
                                    ->where('day_tickets.ticket_id',$Ticket_id)
                                    ->get();

                return ['ticket' => $Ticket, 'ticketDetail'=>$detail,'client'=>$client, 'days'=>$days];
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

        switch ($Ticket->active) {
            case 0:
                $Ticket->active = 1;
            break;
            case 1:
                $Ticket->active = 0;  
            break;
        }

        if ($Ticket->save()) {
            return $Ticket;
        }

        throw new GeneralException(__('Error changing status of Ticket.'));
    }

    public function deleteOrResotore($Ticket_id)
    {   
        
        return DB::transaction(function () use ($Ticket_id) {
        
            $ticket = Ticket::find($Ticket_id);

            $coins =Coin_purse::where('user_id',Auth::user()->id)->first();
            $coins->coins += $ticket['total'];

            if ($coins->save()) {
             
                    if(Ticket::find($ticket['id'])->delete()){
                        return $coins;
                    }
                throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
            }
            throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
        });
    
        
    }

}
