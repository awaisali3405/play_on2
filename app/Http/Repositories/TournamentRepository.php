<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Tournament;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use stdClass;

class TournamentRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Tournament();
    }

    public function get($id): Tournament
    {
        try{
            if($id == 0){
                $Tournament = $this->getModel();

                return $Tournament;
            } else {
                return $this->getModel()->findOrFail($id);
            }
        } catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }

    public function all()
    {
        try{
            return Tournament::all();
        } catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }

    public function save($data, $id)
    {
        try{
            return $this->getModel()->updateOrCreate(['id' => $id],$data);
        } catch (Exception $exception){
            return throw new Exception($exception->getMessage());
        }
    }

    public function remove()
    {
        // TODO: Implement remove() method.
    }

    public function destroy($id): bool
    {
        try {
            $this->get($id)->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

}
