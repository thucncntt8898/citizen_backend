<?php

namespace App\Services;

use App\Repositories\Occupation\OccupationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OccupationService extends Service
{
    protected $occupationRepository;
    /**
     * ProvinceService constructor.
     */
    public function __construct(
        OccupationRepositoryInterface $occupationRepository
    ){
        $this->occupationRepository = $occupationRepository;
    }

    public function getListOccupations($name)
    {
        return $this->occupationRepository->getListOccupations($name);
    }

    public function createProvince($params)
    {
        $this->userRepository->create($params);
    }

}
