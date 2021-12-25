<?php

namespace App\Repositories\Ward;

use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WardRepository extends Repository implements WardRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Ward::class;
    }

    public function getListWards($params)
    {
        $count = $this->__getListWards($params)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        } else {
            $data = $this->__getListWards($params)
                ->groupBy('wards.id')
                ->select(
                    'wards.id',
                    'wards.name',
                    'wards.code',
                )
                ->forPage($params['page'], $params['limit'])
                ->get();

            foreach ($data as $ward) {
                $ward->hamlets = $ward->hamlets()->get();
            }
            return [
                'count' => $count,
                'data_list' => $data->toArray()
            ];
        }
    }

    public function __getListWards($params)
    {
        $query = $this->_model::where('wards.district_id', '=', $params['id'])
            ->leftJoin('hamlets', 'hamlets.ward_id', '=', 'wards.code');

        if (!empty($params['province_ids'])) {
            $query->whereIn('provinces.id', $params['province_ids']);
        }
        return $query;

    }

    public function createWard($params)
    {
        DB::beginTransaction();
        try {
            $provinceId = Auth::user()->province_id;
            $districtId = Auth::user()->district_id;
            $provinceCode = Province::find($provinceId)->code;
            $districtCode = District::find($districtId)->code;

            $ward = Ward::create(
                [
                    'code'=> Auth::user()->username.sprintf('%02d', $params['code']),
                    'name' => $params['name'],
                    'province_id' => $provinceId,
                    'district_id' => $districtId,
                ]
            );
            $user = User::create(
                [
                    'username'=> Auth::user()->username.sprintf('%02d', $params['code']),
                    'password' => bcrypt('1234567a'),
                    'province_id' => $provinceId,
                    'district_id' => $districtId,
                    'ward_id' => $ward->id,
                    'role' => config('constants.ROLES.WARD'),
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
     *
     * @return bool
     */
    public function updateWard($params)
    {
        DB::beginTransaction();
        try {
            if (!Ward::where('id', $params['id'])->exists()) {
                return false;
            }

            Ward::where('id', $params['id'])->update(
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
    public function deleteWard($id)
    {
        $provinceCode = Ward::where('id', $id)->first()->code;

        DB::beginTransaction();
        try {
            User::where('address_id', $provinceCode)->delete();
            Ward::where('id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

}