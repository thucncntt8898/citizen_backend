<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitizenCreateRequest;
use App\Http\Requests\CitizenUpdateRequest;
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
       $params = $request->all();
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

    public function updateCitizen(CitizenUpdateRequest $request)
    {
        $params = $request->all();
        $this->citizenService->updateCitizen($params);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!'
        ]);
    }
}
