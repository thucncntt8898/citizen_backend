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
        $count = $this->__getListUsers($params)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        }

        $data = $this->__getListUsers($params)
            ->select(
                'provinces.id as province_id',
                'provinces.name as province_name',
                'districts.id as district_id',
                'districts.name as district_name',
                'wards.id as ward_id',
                'wards.name as ward_name',
                'hamlets.id as hamlet_id',
                'hamlets.name as hamlet_name',
                'users.id',
                'users.username',
                'users.status',
                'users.time_start',
                'users.time_finish'
            )
            ->forPage($params['page'], $params['limit'])->get()->toArray();
        return [
            'count' => $count,
            'data_list' => $data
        ];
    }

    public function __getListUsers($params)
    {
        $query = $this->_model::leftJoin('provinces', 'provinces.id', '=', 'users.province_id')
            ->leftJoin('districts', 'districts.id', '=', 'users.district_id')
            ->leftJoin('wards', 'wards.id', '=', 'users.ward_id')
            ->leftJoin('hamlets', 'hamlets.id', '=', 'users.hamlet_id')
            ->where('role', $params['role'] + 1);
        switch ($params['role']) {
            case $params['roles'][1]://thanh pho
                $query = $query->where('districts.province_id', $params['address_id']);
                break;
            case $params['roles'][2]://quan huyen
                $query = $query->where('wards.district_id', $params['address_id']);
                break;
            case $params['roles'][3]://phuong xa
                $query = $query->where('hamlets.ward_id', $params['address_id']);
                break;
        }
        return $query;
    }

    public function getUserById($userId)
    {
        return $this->_model::where('id', $userId)->first()->toArray();
    }

    public function getUserByProvinceId($provinceId)
    {
        return $this->_model::where('province_id', $provinceId)->pluck('id')->toArray();
    }

    public function getUserByDistrictId($districtId)
    {
        return $this->_model::where('district_id', $districtId)->pluck('id')->toArray();
    }

    public function getUserByWardId($wardId)
    {
        return $this->_model::where('ward_id', $wardId)->pluck('id')->toArray();
    }

    public function getUserByHamletId($hamletId)
    {
        return $this->_model::where('hamlet_id', $hamletId)->pluck('id')->toArray();
    }
}
