<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getListUsers($params);
    public function getUserById($userId);
    public function getUserByProvinceId($provinceId);
    public function getUserByDistrictId($districtId);
    public function getUserByWardId($wardId);
    public function getUserByHamletId($hamletId);
}
