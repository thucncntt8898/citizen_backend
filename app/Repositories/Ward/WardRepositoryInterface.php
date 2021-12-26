<?php

namespace App\Repositories\Ward;

interface WardRepositoryInterface
{
    public function getListWards($params);

    public function createWard($params);

    public function updateWard($params);

    public function deleteWard($id);

    public function getStatisticalWardData();

}
