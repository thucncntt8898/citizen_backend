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
        $query = $this->_model;

        if (Auth::user()->district_id != null) {
            $action = '=';
            $compare = Auth::user()->district_id;
        } else {
            $action = '!=';
            $compare = 0;
        }

        if (Auth::user()->province_id != null) {
            $query = $query->where('wards.province_id','=', Auth::user()->province_id);

        } else {
            $query = $query->where('wards.province_id','!=', 0);
        }

        $query = $query->where('wards.district_id', $action, $compare)
            ->leftJoin('hamlets', 'hamlets.ward_id', '=', 'wards.id');

        if (!empty($params['province_ids'])) {
            $query = $query->whereIn('wards.province_id', $params['province_ids']);
        }

        if (!empty($params['district_ids'])) {
            $query = $query->whereIn('wards.district_id', $params['district_ids']);
        }

        if (!empty($params['ward_ids'])) {
            $query = $query->whereIn('wards.id', $params['ward_ids']);
        }

        if (!empty($params['code'])) {
            $query = $query->where('wards.code', 'like', '%' . $params['code'] . '%');
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

    public function getAllWards($provinceId, $districtId)
    {
        $query = $this->_model;
        if (!empty($provinceId)) {
            $query = $query->where('province_id', $provinceId);
        }

        if (!empty($districtId)) {
            $query = $query->where('district_id', $provinceId);
        }
        return $query->get()->toArray();
    }

    public function getStatisticalWardData() {
        $doingWards = count($this->__getStatisticalStatusWardData('doing')->get());
        $doneWards = count($this->__getStatisticalStatusWardData('done')->get());
        $todoWards = count($this->__getStatisticalStatusWardData('todo')->get());


        $data = $this->__getStatisticalWardData()
            ->groupBy('wards.id')
            ->select(
                'wards.id',
                'wards.name',
                'wards.code',
                DB::raw("count(citizens.id) AS total_citizens")
            )
            ->orderBy('total_citizens', 'DESC')
            ->limit(5)
            ->get()->toArray();

        $data['doing'] = $doingWards;
        $data['done'] = $doneWards;
        $data['todo'] = $todoWards;
        return [
            'data_list' => $data
        ];
    }

    public function __getStatisticalWardData()
    {
        return $this->_model::where('wards.province_id','=',Auth::user()->province_id)
            ->where('wards.district_id','=',Auth::user()->district_id)
            ->leftJoin('citizens', 'citizens.permanent_address_ward', '=', 'wards.id');
    }

    public function __getStatisticalStatusWardData($type)
    {
        if ($type == 'doing') {
            return $this->_model::where( 'users.time_finish', '>=', Carbon::now() )
                ->where( 'users.time_start', '<', Carbon::now() )
                ->where( 'users.hamlet_id', '=', null )
                ->leftJoin('users', 'users.ward_id', '=', 'wards.id');
        }

        if ($type == 'done') {
            return $this->_model::where( 'users.time_finish', '<', Carbon::now() )
                ->where( 'users.hamlet_id', '=', null )
                ->leftJoin('users', 'users.ward_id', '=', 'wards.id');
        }

        if ($type == 'todo') {
            return $this->_model::where( 'users.time_finish','=',null)
                ->where( 'users.time_start','=',null)
                ->where( 'users.hamlet_id','=', null )
                ->leftJoin('users', 'users.ward_id', '=', 'wards.id');
        }
    }
}
