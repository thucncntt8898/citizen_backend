<?php

namespace App\Repositories\Hamlet;

interface HamletRepositoryInterface
{
    public function getListHamlets($params);

    public function createHamlet($params);

    public function updateHamlet($params);

    public function deleteHamlet($id);

}
