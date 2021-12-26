<?php

namespace App\Repositories\Hamlet;

interface HamletRepositoryInterface
{
    public function getListHamlets($params);

    public function createHamlet($params);

    public function updateHamlet($params);

    public function deleteHamlet($id);

    public function getAllHamlets($provinceId, $districtId, $wardId);

    public function getStatisticalHamletData();

    public function completeStatistical($params);
}
