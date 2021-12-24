<?php

namespace App\Repositories\Hamlet;

use App\Models\Hamlet;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HamletRepository extends Repository implements HamletRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Hamlet::class;
    }

    public function getListHamlets($params)
    {
        $count = $this->__getListHamlets($params)->count();
        if ($count == 0) {
            return [
                'count' => 0,
                'data_list' => []
            ];
        } else {
            $data = $this->__getListHamlets($params)
                ->groupBy('hamlets.id')
                ->select(
                    'hamlets.id',
                    'hamlets.name',
                    'hamlets.code',
                )
                ->forPage($params['page'], $params['limit'])
                ->get();

            return [
                'count' => $count,
                'data_list' => $data->toArray()
            ];
        }
    }

    public function __getListHamlets($params)
    {
        $query = $this->_model::where('hamlets.ward_id', '=', $params['id']);
        if (!empty($params['province_ids'])) {
            $query->whereIn('provinces.id', $params['province_ids']);
        }
        return $query;

    }

    public function createHamlet($params)
    {
        DB::beginTransaction();
        try {
            $provinceCode = sprintf('%02d', Auth::user()->province_id);
            $districtCode = sprintf('%02d', Auth::user()->district_id);
            $wardCode = sprintf('%02d', Auth::user()->ward_id);

            Hamlet::create(
                [
                    'code'=> $provinceCode.$districtCode.$wardCode.sprintf('%02d', $params['code']),
                    'name' => $params['name'],
                    'province_id' => $provinceCode,
                    'district_id' => $districtCode,
                    'ward_id' => $wardCode,
                    'hamlet_id' => sprintf('%02d', $params['code']),
                ]
            );
            $user = User::create(
                [
                    'username'=> $provinceCode.$districtCode.$wardCode.sprintf('%02d', $params['code']),
                    'password' => bcrypt('1234567a'),
                    'province_id' => $provinceCode,
                    'district_id' => $districtCode,
                    'ward_id' => $wardCode,
                    'hamlet_id' => sprintf('%02d', $params['code']),
                    'role' => config('constants.ROLES.HAMLET'),
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
    public function updateHamlet($params)
    {
        DB::beginTransaction();
        try {
            if (!Hamlet::where('id', $params['id'])->exists()) {
                return false;
            }

            Hamlet::where('id', $params['id'])->update(
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
    public function deleteHamlet($id)
    {
        $provinceCode = Hamlet::where('id', $id)->first()->code;

        DB::beginTransaction();
        try {
            User::where('address_id', $provinceCode)->delete();
            Hamlet::where('id', $id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

}
