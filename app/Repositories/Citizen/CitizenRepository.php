<?php

namespace App\Repositories\Citizen;

use App\Repositories\Repository;

class CitizenRepository extends Repository implements CitizenRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Citizen::class;
    }

    public function getListCitizens($params)
    {
       $count = $this->__getListCitizens($params)->count();
       if ($count == 0) {
           return [
               'count' => 0,
               'data_list' => []
           ];
       }

       return $this->__getListCitizens($params)
           ->select(
               'citizens.id',
               'citizens.id_card',
               'citizens.fullname',
               'citizens.dob'
           )
           ->forPage($params['page'], $params['limit'])->get()->toArray();
    }

    public function __getListCitizens($params)
    {
        $query = $this->_model;
        switch ($params['role']) {
            case $params['roles'][1]://thanh pho
                $query = $query->where('citizens.permanent_address_province', $params['address_id']);
                break;
            case $params['roles'][2]://quan huyen
                $query = $query->where('citizens.permanent_address_district', $params['address_id']);
                break;
            case $params['roles'][3]://phuong xa
                $query = $query->where('citizens.permanent_address_ward', $params['address_id']);
                break;
            case $params['roles'][4]://xom thon
                $query = $query->where('citizens.permanent_address_hamlet', $params['address_id']);
                break;
        }
        return $query;
    }
}
