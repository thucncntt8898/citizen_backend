<?php

namespace App\Repositories\District;

interface DistrictRepositoryInterface
{
    public function getListDistricts($params, $id);

    public function createDistricts($params);
}
