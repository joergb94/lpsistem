<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Deposit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
/**
 * Class UserRepository.
 */
class RepositoryDeposit
{
    /**
     * UserRepository constructor.
     *
     * @param  User  $model
     */
    public function __construct(Deposit $model)
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
                     ? $this->model->where($criterion, 'like', '%'. $search . '%')
                     : $this->model->where('id','>',0);
                
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
                
                $Users = $rg->orderBy('id', 'desc')->paginate(10);

        return [
                'pagination' => [
                    'total'        => $Users->total(),
                    'current_page' => $Users->currentPage(),
                    'per_page'     => $Users->perPage(),
                    'last_page'    => $Users->lastPage(),
                    'from'         => $Users->firstItem(),
                    'to'           => $Users->lastItem(),
                ],
                'Users' => $Users
            ];
    }

  
    /**
     * @param array $data
     *
     * @throws \Exception
     * @throws \Throwable
     * @return User
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $User = $this->model::create([
                'bank' => $data['bank'],
                'numDep' => $data['numDep'],
                'imageDep' => $data['imageDep'],
                'amount' => $data['amount'],
                'description' => $data['description'],
                'id_user' => Auth::id(),
                'status' => 1,
            ]);

            if ($User) {
                return $User;
            }

            throw new GeneralException(__('There was an error created the User.'));
        });
    }

    /**
     * @param User  $User
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return User
     */
    public function update($User_id, array $data): User
    {
        
        $User = $this->model->find($User_id);
        
        return DB::transaction(function () use ($User, $data) {
            if ($User->update([
                'type_user_id'=>$data['type'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ])) {

                return $User;
            }

            throw new GeneralException(__('There was an error updating the User.'));
        });
    }

     /**
     * @param User  $User
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return User
     */
    public function update_password($User_id, array $data): User
    {
        
        $User = $this->model->find($User_id);
        
        return DB::transaction(function () use ($User, $data) {
            if ($User->update([
                'password' =>Hash::make($data['password']),
            ])) {

                return $User;
            }

            throw new GeneralException(__('There was an error updating the User.'));
        });
    }

    
    /*
     * @param User $User
     * @param      $status
     *
     * @throws GeneralException
     * @return User
     */
     
    public function updateStatus($deposit_id): User
    {
        $deposit = $this->model->find($deposit_id->id);
        $deposit->status = $deposit_id->nStatus;  

        if ($deposit->save()) {
            return $deposit;
        }

        throw new GeneralException(__('Error changing status of User.'));
    }

    public function deleteOrResotore($User_id)
    {    
        $Bval = Deposit::withTrashed()->find($User_id)->trashed();

            if($Bval){
                $User = Deposit::withTrashed()->find($User_id)->restore();
                $b=4;
            }else{
                $User = Deposit::find($User_id)->delete();
                $b=3;
            }

            if ($b) {
                return $b;
            }
    
            throw new GeneralException(__('Error deleteOrResotore of User.'));
    }



}
