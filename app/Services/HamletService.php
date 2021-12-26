<?php

namespace App\Services;

use App\Repositories\Hamlet\HamletRepositoryInterface;

class HamletService extends Service
{
    protected $hamletRepository;

    /**
     * HamletService constructor.
     * @param HamletRepositoryInterface $hamletRepository
     */
    public function __construct(
        HamletRepositoryInterface $hamletRepository
    ){
        $this->hamletRepository = $hamletRepository;
    }

    public function getListHamlets($params)
    {
        return $this->hamletRepository->getListHamlets($params);
    }

    public function createHamlet($params)
    {
        return $this->hamletRepository->createHamlet($params);
    }

    public function updateHamlet($params)
    {
        return $this->hamletRepository->updateHamlet($params);
    }

    public function deleteHamlet($id)
    {
        return $this->hamletRepository->deleteHamlet($id);
    }

    public function getStatisticalHamletData() {
        return $this->hamletRepository->getStatisticalHamletData();
    }


}
