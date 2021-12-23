<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserService extends Service
{
    protected $userRepository;
    /**
     * ProvinceService constructor.
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ){
        $this->userRepository = $userRepository;
    }

    public function getListUsers($params)
    {
        $params['role'] = Auth::user()->role;
        $params['roles'] = array_values(config('constants.ROLES'));
        return $this->userRepository->getListUsers($params);
    }

    public function createProvince($params)
    {
        $this->userRepository->create($params);
    }

}
