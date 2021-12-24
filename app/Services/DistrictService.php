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

    public function getListDistricts($params)
    {
        return $this->districtRepository->getListDistricts($params);
    }

    public function createDistrict($params)
    {
        return $this->districtRepository->createDistricts($params);
    }

    public function updateDistrict(array $params)
    {
        return $this->districtRepository->updateDistrict($params);
    }

    public function deleteDistrict(array $params)
    {
        return $this->districtRepository->deleteDistrict($params);
    }

}
