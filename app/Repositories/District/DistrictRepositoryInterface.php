<?php

namespace App\Repositories\District;

interface DistrictRepositoryInterface
{
    public function getListDistricts($params);

    public function createDistricts($params);

    public function updateDistrict($params);

    public function deleteDistrict($id);

    public function getAllDistricts($provinceId);

}
