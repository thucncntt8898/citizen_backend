<?php

namespace App\Services;

use App\Repositories\Citizen\CitizenRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CitizenService extends Service
{
    protected $citizenRepository;
    /**
     * ProvinceService constructor.
     */
    public function __construct(
         CitizenRepositoryInterface $citizenRepository
    ){
        $this->citizenRepository = $citizenRepository;
    }

    public function getListCitizens($params)
    {
        return $this->citizenRepository->getListCitizens($params);
    }

    public function createCitizen($params)
    {
        $this->citizenRepository->create($params);
    }

}
