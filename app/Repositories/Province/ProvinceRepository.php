<?php

namespace App\Repositories\Province;

use App\Models\District;
use App\Models\Hamlet;
use App\Models\Province;
use App\Models\Ward;
use App\Repositories\Repository;

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
                    'provinces.code'
                )
                ->forPage($params['page'], $params['limit'])
                ->get()->toArray();
            return [
                'count' => $count,
                'data_list' => $data
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
}
