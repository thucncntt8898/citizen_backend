<?php

namespace App\Http\Controllers;

use App\Services\OccupationService;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    protected $occupationService;

    public function __construct(
        OccupationService $occupationService
    )
    {
        $this->occupationService = $occupationService;
    }

    public function getListOccupations(Request $request)
    {
        $name = (isset($request->name) && !empty($request->name)) ? $request->name : '';
        $data = $this->occupationService->getListOccupations($name);
        return response()->json([
           'success' => true,
           'data' => $data
        ]);
    }
}
