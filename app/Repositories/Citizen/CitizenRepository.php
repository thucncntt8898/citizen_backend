<?php

namespace App\Repositories\Citizen;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

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

        $data = $this->__getListCitizens($params)
            ->select(
                'citizens.id',
                'citizens.id_card',
                'citizens.fullname',
                'citizens.dob',
                'citizens.gender',
                'citizens.native_address',
                'citizens.temp_address',
                'citizens.religion',
                'citizens.edu_level',
                'citizens.occupation',
                'citizens.created_at',
                'citizens.updated_at',
                'provinces.id as province_id',
                'provinces.name as province_name',
                'districts.id as district_id',
                'districts.name as district_name',
                'wards.id as ward_id',
                'wards.name as ward_name',
                'hamlets.id as hamlet_id',
                'hamlets.name as hamlet_name'
            )
            ->forPage($params['page'], $params['limit'])->get()->toArray();

        return [
            'count' => $count,
            'data_list' => $data
        ];
    }

    public function __getListCitizens($params)
    {
        $roles = array_values(config('constants.ROLES'));
        $user = Auth::user();
        $query = $this->_model::leftJoin('provinces', 'provinces.id', '=', 'citizens.permanent_address_province')
            ->leftJoin('districts', 'districts.id', '=', 'citizens.permanent_address_district')
            ->leftJoin('wards', 'wards.id', '=', 'citizens.permanent_address_ward')
            ->leftJoin('hamlets', 'hamlets.id', '=', 'citizens.permanent_address_hamlet');
        switch ($user->role) {
            case $roles[1]://thanh pho
                $query = $query->where('citizens.permanent_address_province', $user->province_id);
                break;
            case $roles[2]://quan huyen
                $query = $query->where('citizens.permanent_address_district', $user->district_id);
                break;
            case $roles[3]://phuong xa
                $query = $query->where('citizens.permanent_address_ward', $user->ward_id);
                break;
            case $roles[4]://xom thon
                $query = $query->where('citizens.permanent_address_hamlet', $user->hamlet_id);
                break;
        }
        return $query;
    }
}
