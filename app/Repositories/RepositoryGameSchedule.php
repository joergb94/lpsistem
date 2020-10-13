<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Game_schedule;
use App\Models\Game;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class Game_scheduleRepository.
 */
class RepositoryGameSchedule
{
    /**
     * Game_scheduleRepository constructor.
     *
     * @param  Game_schedule  $model
     */
    public function __construct(Game_schedule $model)
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
                     ? $this->model->where($criterion, 'like', '%'. $search . '%')->with('games')
                     : $this->model->where('id','>',0)->with('games');
                
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
                
                $Game_schedules = $rg->orderBy('id', 'desc')->paginate(10);

        return [
                'pagination' => [
                    'total'        => $Game_schedules->total(),
                    'current_page' => $Game_schedules->currentPage(),
                    'per_page'     => $Game_schedules->perPage(),
                    'last_page'    => $Game_schedules->lastPage(),
                    'from'         => $Game_schedules->firstItem(),
                    'to'           => $Game_schedules->lastItem(),
                ],
                'Game_schedules' => $Game_schedules,
                'Games'=>Game::all(),
            ];
    }

  
    /**
     * @param array $data
     *
     * @throws \Exception
     * @throws \Throwable
     * @return Game_schedule
     */
    public function create(array $data): Game_schedule
    {
        return DB::transaction(function () use ($data) {
            $Game_schedule = $this->model::create([
                'game_id' => $data['game_id'],
                'number_win' => $data['number_win'],
                'number_win2' => $data['number_win2'],
                'date' => $data['date'],
            ]);

            if ($Game_schedule) {
                
                    return $Game_schedule;
                
            }

            throw new GeneralException(__('There was an error created the Game_schedule.'));
        });
    }

    /**
     * @param Game_schedule  $Game_schedule
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return Game_schedule
     */
    public function update($Game_schedule_id, array $data)
    {
        
        $Game_schedule = $this->model->find($Game_schedule_id);
        
        return DB::transaction(function () use ($Game_schedule, $data) {
            if ($Game_schedule->update([
                'game_id' => $data['game_id'],
                'number_win' => $data['number_win'],
                'number_win2' => $data['number_win2'],
                'date' => $data['date'],
            ])) {

                return $Game_schedule;
            }

            throw new GeneralException(__('There was an error updating the Game_schedule.'));
        });
    }
 
    /*
     * @param Game_schedule $Game_schedule
     * @param      $status
     *
     * @throws GeneralException
     * @return Game_schedule
     */
     
    public function updateStatus($Game_schedule_id): Game_schedule
    {
        $Game_schedule = $this->model->find($Game_schedule_id);

        switch ($Game_schedule->active) {
            case 0:
                $Game_schedule->active = 1;
            break;
            case 1:
                $Game_schedule->active = 0;  
            break;
        }

        if ($Game_schedule->save()) {
            return $Game_schedule;
        }

        throw new GeneralException(__('Error changing status of Game_schedule.'));
    }

    public function deleteOrResotore($Game_schedule_id)
    {    
        $Bval = Game_schedule::withTrashed()->find($Game_schedule_id)->trashed();

            if($Bval){
                Game_schedule::withTrashed()->find($Game_schedule_id)->restore();
                $b=4;
            }else{
                $Game_schedule = Game_schedule::find($Game_schedule_id)->delete();
                $b=3;
            }

            if ($b) {
                return $b;
            }
    
            throw new GeneralException(__('Error deleteOrResotore of Game_schedule.'));
    }



}
