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
    )
    {
        $this->userRepository = $userRepository;
    }

    public function getListUsers($params)
    {
        $params['role'] = Auth::user()->role;
        $params['roles'] = array_values(config('constants.ROLES'));
        $params['address_id'] = Auth::user()->address_id;
        return $this->userRepository->getListUsers($params);
    }

    public function createProvince($params)
    {
        $this->userRepository->create($params);
    }

    public function updateUser($userId, $params)
    {
        $user = $this->userRepository->getUserById($userId);
        $roles = array_values(config('constants.ROLES'));
        $this->userRepository->update($userId, $params);
        if (!empty($params['status']) || !empty($params['time_start']) || !empty($params['time_finish'])) {
            $userIds = [];
            switch ($user['role']) {
                case $roles[1]:
                    $userIds = $this->userRepository->getUserByProvinceId($user['province_id']);
                    break;
                case $roles[2]:
                    $userIds = $this->userRepository->getUserByDistrictId($user['district_id']);
                    break;
                case $roles[3]:
                    $userIds = $this->userRepository->getUserByWardId($user['ward_id']);
                    break;
                case $roles[4]:
                    $userIds = $this->userRepository->getUserByHamletId($user['hamlet_id']);
                    break;
            }

            $this->userRepository->updateAll(['id' => $userIds],
                ['status' => $params['status'],
                    'time_start' => $params['time_start'],
                    'time_finish' => $params['time_finish']]);
        }
    }

}
