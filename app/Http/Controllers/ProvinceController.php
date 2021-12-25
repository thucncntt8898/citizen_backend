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
        $params['province_ids'] = isset($request->province_ids) && empty(array_filter($request->province_ids)) ? [] : $request->province_ids;
        $params['page'] = empty($request->page) ? 10 : $request->page;
        $params['limit'] = empty($request->limit) ? 1 : $request->limit;
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

    public function updateProvince(Request $request)
    {
        try {
            $params = $request->only(['name', 'id']);
            $created = $this->provinceService->updateProvince($params);

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

    public function deleteProvince($id)
    {
        try {
            $created = $this->provinceService->deleteProvince($id);

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
