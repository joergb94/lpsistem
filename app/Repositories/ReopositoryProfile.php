<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepository.
 */
class RepositoryProfile
{
    /**
     * UserRepository constructor.
     *
     * @param  User  $model
     */
    public function __construct(User $model)
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
    public function get_info($id)
    {
        $Users = $this->model->where('id',$id)->first();
        return ['Users' => $Users];
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

    public function deleteOrResotore($User_id)
    {    
        $Bval = User::withTrashed()->find($User_id)->trashed();

            if($Bval){
                $User = User::withTrashed()->find($User_id)->restore();
                $b=4;
            }else{
                $User = User::find($User_id)->delete();
                $b=3;
            }

            if ($b) {
                return $b;
            }
    
            throw new GeneralException(__('Error deleteOrResotore of User.'));
    }

}
