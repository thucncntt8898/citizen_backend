<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinceRequest;
use App\Models\Province;
use App\Services\ProvinceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * @var ProvinceService
     */
    protected $provinceService;

    /**
     * ProvinceController constructor.
     * @param ProvinceService $provinceService
     */
    public function __construct(
        ProvinceService $provinceService
    )
    {
        $this->provinceService = $provinceService;
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatisticalData(Request $request)
    {
        switch (Auth::user()->role) {
            case config('constants.ROLES.GENERAL'):

                $data = $this->provinceService->getStatisticalProvinceData();

                dd($data);
                break;
            case config('constants.ROLES.PROVINCE'):
                break;
            case config('constants.ROLES.DISTRICT'):
                break;
            case config('constants.ROLES.WARD'):
                break;
            case config('constants.ROLES.HAMLET'):
                break;

        }
    }




}
