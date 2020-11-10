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
class RepositoryManagmentTickets
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


          /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getSearchPaginated($criterion, $search, $status, $date, $seller)
    {   
        $rg = $this->modelDAT->select( 'tickets.id as id',
                                        'tickets.phone as phone',
                                        'tickets.active as active',
                                        'tickets.winner as winner',
                                        'tickets.total as total',
                                        'tickets.deleted_at as deleted_at',
                                        'day_tickets.game_date as date')
                            ->join('tickets','tickets.id', "=", 'day_tickets.ticket_id');

            if(Auth::user()->type_user_id == 3)
            {
                (strlen($criterion) > 0 &&  strlen($search) > 0) 
                ? $rg->where($criterion, 'like', '%'. $search . '%')->where('tickets.seller_id',Auth::user()->id)
                : $rg->where('tickets.id','>',0)->where('tickets.seller_id',Auth::user()->id); 
           
            }
            else{
                (strlen($criterion) > 0 &&  strlen($search) > 0) 
                ? $rg->where($criterion, 'like', '%'. $search . '%')
                : $rg->where('tickets.id','>',0); 

                if($seller != 'all'){
                    $rg->where('tickets.seller_id',$seller);
                }
            }
                
            
                
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
                            default:
                                $rg->where('tickets.active',true);
                        } 
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
                'Games'=>Game::all(),
                'Days'=>Day::all(),
                'Date' =>($date)? Carbon::parse($date)->toDateString(): Carbon::now()->toDateString(),
                'Sellers' =>Auth::user()->type_user_id < 3?User::whereNotIn('type_user_id',[1,5])->get():User::where('type_user_id',3)->get(),
                'type'=>Auth::user()->type_user_id,
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
                'type_user_id'=>5,
                'name' => $data['phone'],
                'phone' => $data['phone'],
                'email' => $data['phone'].'@lp.com',
                'password' =>Hash::make($data['phone']),
            ])
            : User::where('phone',$data['phone'])
                    ->where('type_user_id','>',1)
                    ->first();

            if ($Client) {
                if(Coin_purse::create(['user_id' => $Client->id,
                                        'coins' => 0]))
                {

                $type = ($data['ticket_type'] > 1)? 4 : 0;
                $percentage = Auth::user()->percentage/100;
                for ($i=0; $i <= $type; $i++) { 
                   
                    $Ticket = $this->model::create([
                        'seller_id'=>Auth::user()->id,
                        'user_id' => $Client['id'],
                        'ticket_type'=>$data['ticket_type'],
                        'phone' => $data['phone'],
                        'total_seller' => $data['total']*$percentage,
                        'total_gain' => $data['total'] - ($data['total']*$percentage),
                        'total' => $data['total'],
                        'active'=>false,
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
                            if($now <= $time_end){

                                Day_ticket::create([
                                    'ticket_id'=>$Ticket['id'],
                                    'day_id' => $item['day']['id'],
                                    'game_date'=>$date,
                                ]);

                                foreach ($data['dataNumbers'] as $detail){
                                    $totalDetail +=$detail['subtotal'];
                                    
                                    $this->model_detail::create([
                                        'ticket_id'=>$Ticket['id'],
                                        'user_id' => $Client['id'],
                                        'game_id'=>$data['game']['id'],
                                        'figures'=>$detail['figures'],
                                        'date_ticket'=>$date,
                                        'game_number' => $detail['number'],
                                        'bet' => $detail['subtotal'],
                                        'bet_seller'=>$detail['subtotal']*$percentage,
                                        'bet_gain'=>$detail['subtotal']-($detail['subtotal']*$percentage),
                                        'active'=>false,
                                    ]);
                                }

                            }
                        }

                        $totalDays = $totalDetail ;
                        $this->model->find($Ticket['id'])->update([
                                                                    'total_seller' => $data['total']*$percentage,
                                                                    'total_gain' => $data['total'] - ($data['total']*$percentage),
                                                                    'total'=>$totalDays]);
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
        return DB::transaction(function () use ($Ticket) {

            switch ($Ticket->active) {
                case 0:
                    $Ticket->active = 1;
                break;
                case 1:
                    $Ticket->active = 0;  
                break;
            }

            if ($Ticket->save()) {

                if($this->model_detail->where('ticket_id', $Ticket->id)
                                        ->update(['active' => $Ticket->active]))
                    {
                        if($this->model->find($Ticket->id)
                                       ->update(['charged_id'=>Auth::user()->id])){
                            
                                        return $Ticket;

                        }
                        
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
                $Ticket_detail=TicketDetail::where('ticket_id',$Ticket_id)->restore();
                $b=4;
            }else{
                $Ticket = Ticket::find($Ticket_id)->delete();
                $Ticket_detail=TicketDetail::where('ticket_id',$Ticket_id)->delete();
                $b=3;
            }

            if ($b) {
                return $b;
            }
    
            throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
    }



}
