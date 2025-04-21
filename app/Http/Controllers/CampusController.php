<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * 获取所有园区信息
     * 
     * API: GET /api/v1/campus?token={CLIENT_AUTHORIZATION_TOKEN}
     * 
     * 响应格式:
     * {
     *     "message": "success",
     *     "data": [
     *         {
     *             "id": 1,
     *             "name": "Campus A"
     *         },
     *         ...
     *     ]
     * }
     */
    public function getAllCampuses(Request $request)
    {
        // 从数据库获取所有园区
        $campuses = Campus::all();
        
        // 格式化响应数据
        $formattedCampuses = $campuses->map(function ($campus) {
            return [
                'id' => $campus->id,
                'name' => $campus->name
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $formattedCampuses
        ]);
    }
}