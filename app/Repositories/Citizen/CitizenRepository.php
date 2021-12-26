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
                'hamlets.name as hamlet_name',
                'occupations.id as occupation_id',
                'occupations.name as occupation_name',
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
            ->leftJoin('hamlets', 'hamlets.id', '=', 'citizens.permanent_address_hamlet')
            ->leftJoin('occupations', 'occupations.id', '=', 'citizens.occupation');
        if (array_key_exists('occupation', $params)) {
            $query = $query->whereIn('citizens.occupation', $params['occupation']);
        }
        if (array_key_exists('id_card', $params) && $params['id_card'] != null) {
            $query = $query->where('citizens.id_card', 'like', '%' . $params['id_card'] . '%');
        }
        if (array_key_exists('fullname', $params) && $params['fullname'] != null) {
            $query = $query->where('citizens.fullname', 'like', '%' . $params['fullname'] . '%');
        }
        if (array_key_exists('dob', $params) && $params['dob'] != null) {
            $query = $query->where('citizens.dob', $params['dob']);
        }
        if (array_key_exists('native_address', $params) && $params['native_address'] != null) {
            $query = $query->where('citizens.native_address', 'like', '%' . $params['native_address'] . '%');
        }
        if (array_key_exists('temp_address', $params) && $params['temp_address'] != null) {
            $query = $query->where('citizens.temp_address', 'like', '%' . $params['temp_address'] . '%');
        }
        if (array_key_exists('gender', $params) && $params['gender'] != null && $params['gender'] != 2) {
            $query = $query->where('gender', $params['gender']);
        }
        if (array_key_exists('permanent_address_province', $params)) {
            $query = $query->whereIn('citizens.permanent_address_province', $params['permanent_address_province']);
        }
        if (array_key_exists('permanent_address_district', $params)) {
            $query = $query->whereIn('citizens.permanent_address_district', $params['permanent_address_district']);
        }
        if (array_key_exists('permanent_address_ward', $params)) {
            $query = $query->whereIn('citizens.permanent_address_ward', $params['permanent_address_ward']);
        }
        if (array_key_exists('permanent_address_hamlet', $params)) {
            $query = $query->whereIn('citizens.permanent_address_hamlet', $params['permanent_address_hamlet']);
        }
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
