<?php

namespace App\Http\Controllers;

use App\Services\ProvinceService;
use Illuminate\Http\Request;

class ProvinceController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListProvinces(Request $request)
    {
        $params = $request->only(['province_ids', 'limit', 'page']);
        $data = $this->provinceService->getListProvinces($params);

        return response()->json([
           'success' => true,
           'data' => $data
        ]);
    }

    public function createProvince(Request $request)
    {
        try {
            $params = $request->only(['name', 'code']);
            $created = $this->provinceService->createProvince($params);

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
