<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListUsers(Request $request)
    {
        $params['province_ids'] = isset($request->province_ids) && empty(array_filter($request->province_ids)) ? [] : $request->province_ids;
        $params['district_ids'] = isset($request->district_ids) && empty(array_filter($request->district_ids)) ? [] : $request->district_ids;
        $params['ward_ids'] = isset($request->ward_ids) && empty(array_filter($request->ward_ids)) ? [] : $request->ward_ids;
        $params['hamlet_ids'] = isset($request->hamlet_ids) && empty(array_filter($request->hamlet_ids)) ? [] : $request->hamlet_ids;
        $params['username'] = empty($request->username) ? '' : $request->username;
        $params['status'] = empty($request->status) ? 0 : $request->status;
        $params['is_completed'] = empty($request->is_completed) ? 0 : $request->is_completed;
        $params['page'] = empty($request->page) ? 10 : $request->page;
        $params['limit'] = empty($request->limit) ? 1 : $request->limit;
        $data = $this->userService->getListUsers($params);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        $userId = $request->user_id;
        $params['status'] = $request->status;
        if (!empty($request->password)) {
            $params['password'] = $request->password;
        }
        if (!empty($request->time_start)) {
            $params['time_start'] = $request->time_start;
        }
        if (!empty($request->time_finish)) {
            $params['time_finish'] = $request->time_finish;
        }

        $this->userService->updateUser($userId, $params);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!'
        ]);
    }

    public function getInfoAddress()
    {
        $data = $this->userService->getInfoAddress();

        return response()->json([
           'success' => true,
           'data' => $data
        ]);
    }
}
