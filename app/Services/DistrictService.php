<?php

namespace App\Services;

use App\Repositories\District\DistrictRepositoryInterface;

class DistrictService extends Service
{
    protected $districtRepository;

    /**
     * ProvinceService constructor.
     * @param DistrictRepositoryInterface $districtRepository
     */
    public function __construct(
        DistrictRepositoryInterface $districtRepository
    ){
        $this->districtRepository = $districtRepository;
    }

    public function getListDistricts($params, $id)
    {
        return $this->districtRepository->getListDistricts($params, $id);
    }

    public function createDistrict($params)
    {
        return $this->districtRepository->createDistricts($params);
    }

}
