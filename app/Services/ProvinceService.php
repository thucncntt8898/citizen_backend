<?php

namespace App\Services;

use App\Repositories\Province\ProvinceRepositoryInterface;

class ProvinceService extends Service
{
    protected $provinceRepository;

    /**
     * ProvinceService constructor.
     * @param ProvinceRepositoryInterface $provinceRepository
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
        return $this->provinceRepository->createProvince($params);
    }

    public function updateProvince($params)
    {
        return $this->provinceRepository->updateProvince($params);
    }

    public function deleteProvince($id)
    {
        return $this->provinceRepository->deleteProvince($id);
    }

    public function getStatisticalProvinceData() {
        return $this->provinceRepository->getStatisticalProvinceData();
    }

}
