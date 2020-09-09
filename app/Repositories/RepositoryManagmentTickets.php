<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
    public function __construct(Ticket $model)
    {
        $this->model = $model;
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
                     ? $this->model->where($criterion, 'like', '%'. $search . '%')->whereIn('type_Ticket_id',[2,3])
                     : $this->model->where('id','>',0)->whereIn('type_Ticket_id',[2,3]);
                
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
                'Tickets' => $Tickets
            ];
    }

  
    /**
     * @param array $data
     *
     * @throws \Exception
     * @throws \Throwable
     * @return Ticket
     */
    public function create(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $Ticket = $this->model::create([
                'type_Ticket_id'=>$data['type'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' =>Hash::make($data['password']),
            ]);

            if ($Ticket) {
                return $Ticket;
            }

            throw new GeneralException(__('There was an error created the Ticket.'));
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
    public function update($Ticket_id, array $data): Ticket
    {
        
        $Ticket = $this->model->find($Ticket_id);
        
        return DB::transaction(function () use ($Ticket, $data) {
            if ($Ticket->update([
                'type_Ticket_id'=>$data['type'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ])) {

                return $Ticket;
            }

            throw new GeneralException(__('There was an error updating the Ticket.'));
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
