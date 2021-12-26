<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinceRequest;
use App\Models\Province;
use App\Services\DistrictService;
use App\Services\HamletService;
use App\Services\ProvinceService;
use App\Services\WardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * @var ProvinceService
     */
    protected $provinceService;
    protected $districtService;
    protected $wardService;
    protected $hamletService;

    /**
     * ProvinceController constructor.
     * @param ProvinceService $provinceService
     * @param DistrictService $districtService
     * @param WardService $wardService
     * @param HamletService $hamletService
     */
    public function __construct(
        ProvinceService $provinceService,
        DistrictService $districtService,
        WardService $wardService,
        HamletService $hamletService
    )
    {
        $this->provinceService = $provinceService;
        $this->districtService = $districtService;
        $this->wardService = $wardService;
        $this->hamletService = $hamletService;

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

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
                break;
            case config('constants.ROLES.PROVINCE'):
                $data = $this->districtService->getStatisticalDistrictData();

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
                break;
            case config('constants.ROLES.DISTRICT'):
                $data = $this->wardService->getStatisticalWardData();

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
                break;
            case config('constants.ROLES.WARD'):
                $data = $this->hamletService->getStatisticalHamletData();

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
                break;
            case config('constants.ROLES.HAMLET'):
                break;

        }
    }




}
