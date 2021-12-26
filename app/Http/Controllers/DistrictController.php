<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistrictRequest;
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
     * @return JsonResponse
     */
    public function getListDistricts(Request $request)
    {
        $params = $request->only(['limit', 'page']);
        $data = $this->districtService->getListDistricts($params);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createDistrict(StoreDistrictRequest $request)
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

    public function updateDistrict(Request $request)
    {
        try {
            $params = $request->only(['name', 'id']);
            $created = $this->districtService->updateDistrict($params);

            $response = [
                'success' => true,
                'message' => 'Chỉnh sửa thành công!'
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

    public function deleteDistrict($id)
    {
        try {
            $created = $this->districtService->deleteDistrict($id);

            $response = [
                'success' => true,
                'message' => 'Xóa thành công!'
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
