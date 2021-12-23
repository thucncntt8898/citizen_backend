<?php

namespace App\Repositories\Province;

interface ProvinceRepositoryInterface
{
    public function getListProvinces($params);

    public function createProvinces($params);
}
