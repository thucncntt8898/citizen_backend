<?php

namespace App\Http\Controllers;

use App\Services\HamletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HamletController extends Controller
{
    /**
     * @var HamletService
     */
    protected $hamletService;

    /**
     * HamletController constructor.
     * @param HamletService $hamletService
     */
    public function __construct(
        HamletService $hamletService
    )
    {
        $this->hamletService = $hamletService;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getListHamlets(Request $request)
    {
        $params['page'] = empty($request->page) ? 10 : $request->page;
        $params['limit'] = empty($request->limit) ? 1 : $request->limit;
        $params['id'] = $request->id;
        $data = $this->hamletService->getListHamlets($params);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createHamlet(Request $request)
    {
        try {
            $params = $request->only(['name', 'code']);
            $created = $this->hamletService->createHamlet($params);

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

    public function updateHamlet(Request $request)
    {
        try {
            $params = $request->only(['name', 'id']);
            $created = $this->hamletService->updateHamlet($params);

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

    public function deleteHamlet($id)
    {
        try {
            $created = $this->hamletService->deleteHamlet($id);

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

    public function completeStatistical(Request $request) {
        try {
            $params = $request->only(['status']);
            $complete = $this->hamletService->completeStatistical($params);

            $response = [
                'success' => true,
                'message' => 'Chỉnh sửa thành công!'
            ];

            if (!$complete) {
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
