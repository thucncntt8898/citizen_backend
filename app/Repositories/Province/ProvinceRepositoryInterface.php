<?php

namespace App\Repositories\Province;

interface ProvinceRepositoryInterface
{
    public function getListProvinces($params);

    public function createProvince($params);

    public function updateProvince($params);

    public function deleteProvince($id);

}
