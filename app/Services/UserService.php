<?php

namespace App\Services;

use App\Repositories\District\DistrictRepositoryInterface;
use App\Repositories\Hamlet\HamletRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Ward\WardRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserService extends Service
{
    protected $userRepository;

    protected $provinceRepository;

    protected $districtRepository;

    protected $wardRepository;

    protected $hamletRepository;

    /**
     * ProvinceService constructor.
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        ProvinceRepositoryInterface $provinceRepository,
        DistrictRepositoryInterface $districtRepository,
        WardRepositoryInterface $wardRepository,
        HamletRepositoryInterface $hamletRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->hamletRepository = $hamletRepository;
    }

    public function getListUsers($params)
    {
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
                case $roles[1]://general
                    $userIds = $this->userRepository->getUserByProvinceId($user['province_id']);
                    break;
                case $roles[2]://tinh/thanh pho
                    $userIds = $this->userRepository->getUserByDistrictId($user['district_id']);
                    break;
                case $roles[3]://quan/huyen
                    $userIds = $this->userRepository->getUserByWardId($user['ward_id']);
                    break;
                case $roles[4]://phuong/xa
                    $userIds = $this->userRepository->getUserByHamletId($user['hamlet_id']);
                    break;
            }

            $this->userRepository->updateAll(['id' => $userIds],
                ['status' => $params['status'],
                    'time_start' => $params['time_start'],
                    'time_finish' => $params['time_finish']]);
        }
    }


    public function getInfoAddress()
    {
        $user = Auth::user();
        $roles = array_values(config('constants.ROLES'));

        $data = [];

        switch ($user['role']) {
            case $roles[0]:
                $data['provinces'] = $this->getListProvinces();
                $data['districts'] = $this->getListDistricts();
                $data['wards'] = $this->getListWards();
                $data['hamlets'] = $this->getListHamlets();
                break;
            case $roles[1]:
                $data['districts'] = $this->getListDistricts($user->province_id);
                $data['wards'] = $this->getListWards($user->province_id);
                $data['hamlets'] = $this->getListHamlets($user->province_id);
                break;
            case $roles[2]:
                $data['wards'] = $this->getListWards($user->district_id);
                $data['hamlets'] = $this->getListHamlets($user->district_id);
                break;
            case $roles[3]:
                $data['hamlets'] = $this->getListHamlets($user->ward_id);
                break;
        }

        return $data;
    }

    public function getListProvinces() {
        return $this->provinceRepository->getAllProvinces();
    }

    public function getListDistricts($provinceId = '') {
        return $this->districtRepository->getAllDistricts($provinceId);
    }

    public function getListWards($provinceId = '', $districtId = '') {
        return $this->wardRepository->getAllWards($provinceId, $districtId);
    }

    public function getListHamlets($provinceId = '', $districtId = '', $wardId = '') {
        return $this->hamletRepository->getAllHamlets($provinceId, $districtId, $wardId);
    }

}
