<?php

namespace App\Repositories\District;

use App\Models\District;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistrictRepository extends Repository implements DistrictRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\District::class;
    }

    public function createDistricts($params)
    {

        DB::beginTransaction();
        try {
            $provinceCode = Auth::user()->address_id;
            District::create(
                [
                    'code'=>$params['code'],
                    'name' => $params['name'],
                    'province_id' => $provinceCode
                ]
            );
            $user = User::create(
                [
                    'username'=> $provinceCode.sprintf('%02d', $params['code']),
                    'password' => bcrypt('1234567a'),
                    'address_id' => $provinceCode.sprintf('%02d', $params['code']),
                    'role' => 3,
                    'status' => 0
                ]
            );
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $params
     * @param $id
     * @return mixed
     */
    public function getListDistricts($params, $id)
    {
        $count = $this->__getListDistricts($params, $id)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        } else {
            $data = $this->__getListDistricts($params, $id)
                ->groupBy('districts.id')
                ->select(
                    'districts.id',
                    'districts.name',
                    'districts.code',
                )
                ->forPage($params['page'], $params['limit'])
                ->get();

            foreach ($data as $district) {
                $countHamlet = 0;
                $district->wards = $district->wards()->get();
                foreach ($district->wards as $ward) {
                    $ward->hamlets = $ward->hamlets()->get();
                    $countHamlet = $countHamlet + count($ward->hamlets()->get());
                }

                $district->countHamlet = $countHamlet;
            }

            return [
                'count' => $count,
                'data_list' => $data->toArray()
            ];
        }
    }

    public function __getListDistricts($params, $id)
    {
        $query = $this->_model::where('districts.province_id', '=', $id)
            ->leftJoin('wards', 'wards.province_id', '=', 'districts.id')
            ->leftJoin('hamlets', 'hamlets.province_id', '=', 'districts.id');
        return $query;
    }
}
