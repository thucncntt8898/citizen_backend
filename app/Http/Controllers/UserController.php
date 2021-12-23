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
        $params['province_ids'] = empty(array_filter($request->province_ids)) ? [] : $request->province_ids;
        $params['district_ids'] = empty(array_filter($request->district_ids)) ? [] : $request->district_ids;
        $params['ward_ids'] = empty(array_filter($request->ward_ids)) ? [] : $request->ward_ids;
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
     */
    public function createUser(Request $request)
    {

    }
}