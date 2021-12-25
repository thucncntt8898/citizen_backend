<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitizenCreateRequest;
use App\Services\CitizenService;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    protected $citizenService;

    public function __construct(
        CitizenService $citizenService
    )
    {
        $this->citizenService = $citizenService;
    }

    public function getListCitizens(Request $request)
    {
        $params['province_ids'] = isset($request->province_ids) && empty(array_filter($request->province_ids)) ? [] : $request->province_ids;
        $params['district_ids'] = isset($request->district_ids) && empty(array_filter($request->district_ids)) ? [] : $request->district_ids;
        $params['ward_ids'] = isset($request->ward_ids) && empty(array_filter($request->ward_ids)) ? [] : $request->ward_ids;
        $params['hamlet_ids'] = isset($request->hamlet_ids) && empty(array_filter($request->hamlet_ids)) ? [] : $request->hamlet_ids;
        $params['page'] = empty($request->page) ? 10 : $request->page;
        $params['limit'] = empty($request->limit) ? 1 : $request->limit;
        $data = $this->citizenService->getListCitizens($params);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createCitizen(CitizenCreateRequest $request)
    {
        $params = $request->all();
        $this->citizenService->createCitizen($params);

        return response()->json([
            'success' => true,
            'message' => 'Tạo mới thành công!'
        ]);
    }
}
