<?php

namespace App\Repositories\User;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\User::class;
    }

    public function getListUsers($params)
    {
        $query = $this->_model->where('users.role', '>', $params['role']);
        switch ($params['role']) {
            case $params['roles'][1]:
                if (!empty($params['province_ids'])) {
                    $query = $query->leftJoin('provinces', 'provinces.id', '=', 'users.address_id')
                        ->whereIn('users.address_id', $params['province_ids'])
                        ->select('provinces.id', 'provinces.name');
                }
                break;
            case $params['roles'][2]:
                if (!empty($params['district_ids'])) {
                    $query = $query->leftJoin('districts', 'districts.id', '=', 'users.address_id')
                        ->leftJoin('provinces', 'provinces.id', '=', 'districts.province_id')
                        ->whereIn('users.address_id', $params['district_ids'])
                    ->select('districts.id', 'districts.name', 'province_id');
                    break;
                }
            case $params['roles'][3]:
                if (!empty($params['ward_ids'])) {
                    $query = $query->leftJoin('wards', 'wards.id', '=', 'users.address_id')
                        ->leftJoin('districts', 'districts.id', '=', 'wards.district_id')
                        ->leftJoin('provinces', 'provinces.id', '=', 'districts.province_id')
                        ->whereIn('users.address_id', $params['ward_ids']);
                    break;
                }
        }
        return $query->forPage($params['page'], $params['limit'])->get()->toArray();
    }
}
