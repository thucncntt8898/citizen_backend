<?php

namespace App\Http\Controllers;

use App\Services\DistrictService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * @var DistrictService
     */
    protected $districtService;

    /**
     * ProvinceController constructor.
     * @param DistrictService $districtService
     */
    public function __construct(
        DistrictService $districtService
    )
    {
        $this->districtService = $districtService;
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getListDistricts(Request $request, $id)
    {
        $params = $request->only(['limit', 'page', 'province_id']);
        $data = $this->districtService->getListDistricts($params, $id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createDistrict(Request $request)
    {
        try {
            $params = $request->only(['name', 'code']);
            $created = $this->districtService->createDistrict($params);

            $response = [
                'success' => true,
                'message' => 'Tạo mới thành công!'
            ];

            if (!$created) {
                $response = [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra!'
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Có lỗi xảy ra!'
            ]);
        }
    }
}
