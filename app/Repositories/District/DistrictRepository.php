<?php

namespace App\Repositories\District;

use App\Models\District;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Support\Carbon;
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
            $provinceCode = sprintf('%02d', Auth::user()->province_id);
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
                    'province_id' => $provinceCode.sprintf('%02d', $params['code']),
                    'district_id' => sprintf('%02d', $params['code']),
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
     * @return mixed
     */
    public function getListDistricts($params)
    {
        $count = $this->__getListDistricts($params)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        } else {
            $data = $this->__getListDistricts($params)
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

    public function __getListDistricts($params)
    {
        $query = $this->_model::where('districts.province_id', '=', $params['id'])
            ->leftJoin('wards', 'wards.district_id', '=', 'districts.id')
            ->leftJoin('hamlets', 'hamlets.district_id', '=', 'districts.id');
        return $query;
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public function updateDistrict($params)
    {
        DB::beginTransaction();
        try {
            if (!District::where('id', $params['id'])->exists()) {
                return false;
            }

            District::where('id', $params['id'])->update(
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
    public function deleteDistrict($id)
    {
        $code = District::where('id', $id)->first()->code;

        DB::beginTransaction();
        try {
            User::where('district_id', $code)->delete();
            District::where('id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
