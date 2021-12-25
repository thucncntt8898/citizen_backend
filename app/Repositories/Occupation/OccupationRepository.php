<?php

namespace App\Repositories\Occupation;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class OccupationRepository extends Repository implements OccupationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Occupation::class;
    }

    public function getListOccupations($name)
    {
        $query = $this->_model;
        if (!empty($name)) {
            $query = $query->where('name', 'like', "%$name%");
        }
        return $query->forPage(1, 30)
            ->get()->toArray();
    }
}
