<?php

namespace App\Http\Controllers;

use App\Services\WardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WardController extends Controller
{
    /**
     * @var WardService
     */
    protected $wardService;

    /**
     * WardController constructor.
     * @param WardService $wardService
     */
    public function __construct(
        WardService $wardService
    )
    {
        $this->wardService = $wardService;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getListWards(Request $request)
    {
        $params['province_ids'] = isset($request->province_ids) && empty(array_filter($request->province_ids)) ? [] : $request->province_ids;
        $params['district_ids'] = isset($request->district_ids) && empty(array_filter($request->district_ids)) ? [] : $request->district_ids;
        $params['ward_ids'] = isset($request->ward_ids) && empty(array_filter($request->ward_ids)) ? [] : $request->ward_ids;
        $params['code'] = empty($request->code) ? '' : $request->code;
        $params['page'] = empty($request->page) ? 10 : $request->page;
        $params['limit'] = empty($request->limit) ? 1 : $request->limit;
        $params['id'] = $request->id;
        $data = $this->wardService->getListWards($params);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createWard(Request $request)
    {
        try {
            $params = $request->only(['name', 'code']);
            $created = $this->wardService->createWard($params);

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

    public function updateWard(Request $request)
    {
        try {
            $params = $request->only(['name', 'id']);
            $created = $this->wardService->updateWard($params);

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

    public function deleteWard($id)
    {
        try {
            $created = $this->wardService->deleteWard($id);

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
