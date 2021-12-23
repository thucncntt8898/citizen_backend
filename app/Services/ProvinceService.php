<?php

namespace App\Services;

use App\Repositories\Province\ProvinceRepositoryInterface;

class ProvinceService extends Service
{
    protected $provinceRepository;
    /**
     * ProvinceService constructor.
     */
    public function __construct(
        ProvinceRepositoryInterface $provinceRepository
    ){
        $this->provinceRepository = $provinceRepository;
    }

    public function getListProvinces($params)
    {
        return $this->provinceRepository->getListProvinces($params);
    }

    public function createProvince($params)
    {
        return $this->provinceRepository->createProvinces($params);
    }

}
