<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Game;
use App\Models\TicketDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
                     ? $this->model->where($criterion, 'like', '%'. $search . '%')->where('seller_id',Auth::user()->id)
                     : $this->model->where('id','>',0)->where('seller_id',Auth::user()->id);
                
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
                    $Ticket = $this->model::create([
                        'seller_id'=>Auth::user()->id,
                        'user_id' => $Client['id'],
                        'total' => $data['total'],
                        'active'=>true,
                    ]);

                    if ($Ticket) {
                        
                        foreach ($data['dataNumbers'] as $detail){
                            $this->model_detail::create([
                                'ticket_id'=>$Ticket['id'],
                                'user_id' => $Client['id'],
                                'game_id'=>$detail['game']['id'],
                                'game_number' => $detail['number'],
                                'bet' => $detail['subtotal'],
                                'active'=>true,
                            ]);
                        }
                        
                        if($this->model_detail
                            ->where('ticket_id',$Ticket['id'])
                            ->count() > 0)
                        {
                            return $Ticket;
                        }
                      throw new GeneralException(__('No se pudo crear el ticket intente nuevamente.'));   
                    }
                throw new GeneralException(__('No se pudo crear el ticket intente nuevamente.'));
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
                
                $detail = $this->model_detail->where('ticket_id',$Ticket['id'])->get();
                $client = User::find($Ticket['user_id']);

                return ['ticket' => $Ticket, 'ticketDetail'=>$detail,'client'=>$client];
            }

            throw new GeneralException(__('There was an error on show the Ticket.'));
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
    public function update_password($Ticket_id, array $data): Ticket
    {
        
        $Ticket = $this->model->find($Ticket_id);
        
        return DB::transaction(function () use ($Ticket, $data) {
            if ($Ticket->update([
                'password' =>Hash::make($data['password']),
            ])) {

                return $Ticket;
            }

            throw new GeneralException(__('There was an error updating the Ticket.'));
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
