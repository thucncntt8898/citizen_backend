<?php

namespace App\Services;

use App\Repositories\Ward\WardRepositoryInterface;

class WardService extends Service
{
    protected $wardRepository;

    /**
     * WardService constructor.
     *
     * @param WardRepositoryInterface $wardRepository
     */
    public function __construct(
        WardRepositoryInterface $wardRepository
    ){
        $this->wardRepository = $wardRepository;
    }

    public function getListWards($params)
    {
        return $this->wardRepository->getListWards($params);
    }

    public function createWard($params)
    {
        return $this->wardRepository->createWard($params);
    }

    public function updateWard(array $params)
    {
        return $this->wardRepository->updateWard($params);
    }

    public function deleteWard(array $params)
    {
        return $this->wardRepository->deleteWard($params);
    }

}
