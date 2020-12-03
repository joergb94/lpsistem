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
use Validator; 


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
                            case 3:
                                $rg->where('tickets.active',true)->where('tickets.winner',true);
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
                'Sellers' =>Auth::user()->type_user_id < 3?User::whereNotIn('type_user_id',[1,5])->get():User::where('type_user_id',[3])->get(),
                'type'=>Auth::user()->type_user_id,
                'user'=>Auth::user()->id,
                'week'=>'Para la Semana del '.Carbon::now()->startOfWeek()->toDateString().' al '.Carbon::now()->endOfWeek()->toDateString()
            ];
    }

    public function checkDate($date,$now,$time_end){
        if($date > $now){
            $valdate = true;
         }elseif($date < $now){
             $valdate = false;
         }elseif ($date->format('Y-m-d') == $now->format('Y-m-d')) {
             if(strtotime(date('H:i:s',$now)) > strtotime(date('H:i:s',$time_end))){
                 $valdate = false;
             }else{
                 $valdate = true;
             }
         } 

         return $valdate;
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

                    if(!Coin_purse::where('user_id',$Client->id)->exists()) 
                    {
                        Coin_purse::create(['user_id' => $Client->id,
                                            'coins' => 0]);
                    }

                    if(Coin_purse::where('user_id',$Client->id)->exists())
                    {

                    $type = ($data['ticket_type'] > 0)? $data['ticket_type']: 0;
                    $percentage = ($Client->id !== Auth::user()->id)? Auth::user()->percentage/100:0;
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

                                if(RepositoryManagmentTickets::calculate_duration($date,$now,$time_end) == true){
                                    $dayT=Day_ticket::create([
                                            'ticket_id'=>$Ticket['id'],
                                            'day_id' => $item['day']['id'],
                                            'game_date'=>$date,
                                        ]);

                                    if($dayT){
                                            foreach ($data['dataNumbers'] as $detail){
                                                $totalDetail +=$detail['subtotal'];
                                                
                                            $tcd = $this->model_detail::create([
                                                    'ticket_id'=>$Ticket['id'],
                                                    'user_id' => $Client['id'],
                                                    'game_id'=>$data['game']['id'],
                                                    'figures'=>$detail['figures'],
                                                    'date_ticket'=>$date,
                                                    'game_number' => $detail['number'],
                                                    'bet' => $detail['subtotal'],
                                                    'bet_seller'=>$detail['subtotal']*$percentage,
                                                    'bet_gain'=>$detail['subtotal']-($detail['subtotal']*$percentage),
                                                    'prize'=>0,
                                                    'active'=>false,
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
                    
                }
               
                return 'exito';
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
                 
                $Bval = $this->model->find($Ticket_id)->exists();
                if($Bval){
                    $Ticket = $this->model->find($Ticket_id)->delete();
                    $Ticket_detail=$this->model_detail->where('ticket_id',$Ticket_id)->delete();
                    $b=3;

                }else{
                    $Ticket = $this->model->withTrashed()->find($Ticket_id)->restore();
                    $Ticket_detail=$this->model_detail->where('ticket_id',$Ticket_id)->restore();
                    $b=4;
                }

                if ($b) {
                    return $b;
                }
    
            throw new GeneralException(__('Error deleteOrResotore of Ticket.'));
    }



}
