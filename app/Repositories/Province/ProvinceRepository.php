<?php

namespace App\Repositories\Province;

use App\Models\District;
use App\Models\Hamlet;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use App\Repositories\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProvinceRepository extends Repository implements ProvinceRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Province::class;
    }

    public function getListProvinces($params)
    {
        $count = $this->__getListProvinces($params)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        } else {
            $data = $this->__getListProvinces($params)
                ->groupBy('provinces.id')
                ->select(
                    'provinces.id',
                    'provinces.name',
                    'provinces.code',
                )
                ->forPage($params['page'], $params['limit'])
                ->get();

            $arrayData = array();
            foreach ($data as $province) {
                $countWard = 0;
                $countHamlet = 0;
                $province->districts = $province->districts()->get();
                foreach ($province->districts as $district) {
                    $district->wards = $district->wards()->get();
                    $countWard = $countWard + count($district->wards()->get());
                    foreach ($district->wards as $ward) {
                        $ward->hamlets = $ward->hamlets()->get();
                        $countHamlet = $countHamlet + count($ward->hamlets()->get());
                    }
                }

                $province->countWard = $countWard;
                $province->countHamlet = $countHamlet;
            }

            return [
                'count' => $count,
                'data_list' => $data->toArray()
            ];
        }
    }

    public function __getListProvinces($params)
    {
        $query = $this->_model::leftJoin('districts', 'districts.province_id', '=', 'provinces.id')
            ->leftJoin('wards', 'wards.province_id', '=', 'provinces.id')
            ->leftJoin('hamlets', 'hamlets.province_id', '=', 'provinces.id');
        if (!empty($params['province_ids'])) {
            $query->whereIn('provinces.id', $params['province_ids']);
        }
        return $query;

    }

    public function createProvince($params)
    {
        DB::beginTransaction();
        try {
            $province = Province::create(['code' => sprintf('%02d', $params['code']), 'name' => $params['name']]);
            User::create(
                [
                    'username' => sprintf('%02d', $params['code']),
                    'password' => Hash::make('citizen' . $params['code']),
                    'province_id' => $province->id,
                    'role' => config('constants.ROLES.PROVINCE')
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
     *
     * @return bool
     */
    public function updateProvince($params)
    {
        DB::beginTransaction();
        try {
            Province::where('id', $params['id'])->update(
                [
                    'name' => $params['name'],
                    'updated_at' => Carbon::now()
                ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteProvince($id)
    {
        $provinceCode = Province::where('id', $id)->first()->code;

        DB::beginTransaction();
        try {
            User::where('address_id', $provinceCode)->delete();
            Province::where('id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

}
