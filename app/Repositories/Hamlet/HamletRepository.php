<?php

namespace App\Repositories\Hamlet;

use App\Models\District;
use App\Models\Hamlet;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
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
        $query = $this->_model;

        if (Auth::user()->province_id != null) {
            $query = $query->where('hamlets.province_id', Auth::user()->province_id);
        } else {
            $query = $query->where('hamlets.province_id','!=', 0);
        }

        if (Auth::user()->district_id != null) {
            $query = $query->where('hamlets.district_id', Auth::user()->district_id);

        } else {
            $query = $query->where('hamlets.district_id','!=', 0);
        }

        if (Auth::user()->ward_id != null) {
            $action = '=';
            $compare = Auth::user()->ward_id;
        } else {
            $action = '!=';
            $compare = 0;
        }
        $query = $query->where('hamlets.ward_id', $action, $compare);
        if (!empty($params['province_ids'])) {
            $query = $query->whereIn('hamlets.province_id', $params['province_ids']);
        }

        if (!empty($params['district_ids'])) {
            $query = $query->whereIn('hamlets.district_id', $params['district_ids']);
        }

        if (!empty($params['ward_ids'])) {
            $query = $query->whereIn('hamlets.id', $params['ward_ids']);
        }

        if (!empty($params['hamlet_ids'])) {
            $query = $query->whereIn('hamlets.id', $params['hamlet_ids']);
        }

        if (!empty($params['code'])) {
            $query = $query->where('hamlets.code', 'like', '%' . $params['code'] . '%');
        }
        return $query;

    }

    public function createHamlet($params)
    {
        DB::beginTransaction();
        try {
            $provinceId = Auth::user()->province_id;
            $districtId = Auth::user()->district_id;
            $wardId = Auth::user()->ward_id;
            $provinceCode = Province::find($provinceId)->code;
            $districtCode = District::find($districtId)->code;
            $wardCode = Ward::find($wardId)->code;


            $hamlet = Hamlet::create(
                [
                    'code'=> Auth::user()->username.sprintf('%02d', $params['code']),
                    'name' => $params['name'],
                    'province_id' => $provinceId,
                    'district_id' => $districtId,
                    'ward_id' => $wardId,
                ]
            );
            $user = User::create(
                [
                    'username'=> Auth::user()->username.sprintf('%02d', $params['code']),
                    'password' => bcrypt('1234567a'),
                    'province_id' => $provinceId,
                    'district_id' => $districtId,
                    'ward_id' => $wardId,
                    'hamlet_id' => $hamlet->id,
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

    public function getAllHamlets($provinceId, $districtId, $wardId)
    {
        $query = $this->_model;
        if (!empty($provinceId)) {
            $query = $query->where('province_id', $provinceId);
        }

        if (!empty($districtId)) {
            $query = $query->where('district_id', $provinceId);
        }

        if (!empty($wardId)) {
            $query = $query->where('ward_id', $wardId);
        }

        return $query->get()->toArray();
    }

    public function getStatisticalHamletData() {
        $doingHamlets = count($this->__getStatisticalStatusHamletData('doing')->get());
        $doneHamlets = count($this->__getStatisticalStatusHamletData('done')->get());
        $todoHamlets =
            count($this->_model::where('district_id', '=', Auth::user()->district_id)->get()) - $doingHamlets - $doneHamlets;

        $data = $this->__getStatisticalHamletData()
            ->groupBy('hamlets.id')
            ->select(
                'hamlets.id',
                'hamlets.name',
                'hamlets.code',
                DB::raw("count(citizens.id) AS total_citizens")
            )
            ->orderBy('total_citizens', 'DESC')
            ->limit(5)
            ->get()->toArray();

        $data['doing'] = $doingHamlets;
        $data['done'] = $doneHamlets;
        $data['todo'] = $todoHamlets;
        return [
            'data_list' => $data
        ];
    }

    public function __getStatisticalHamletData()
    {
        return $this->_model::where('hamlets.province_id','=',Auth::user()->province_id)
            ->where('hamlets.district_id','=',Auth::user()->district_id)
            ->where('hamlets.ward_id','=',Auth::user()->ward_id)
            ->leftJoin('citizens', 'citizens.permanent_address_hamlet', '=', 'hamlets.id');
    }

    public function __getStatisticalStatusHamletData($type)
    {
        if ($type == 'doing') {
            return $this->_model::where( 'users.time_finish', '>=', Carbon::now() )
                ->where( 'users.time_start', '<', Carbon::now() )
                ->leftJoin('users', 'users.hamlet_id', '=', 'hamlets.id');
        }

        if ($type == 'done') {
            return $this->_model::where( 'users.time_finish', '<', Carbon::now() )
                ->leftJoin('users', 'users.hamlet_id', '=', 'hamlets.id');
        }
    }

}
