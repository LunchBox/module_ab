<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function getInformation(Request $request)
    {
        $staff = $request->user();
        
        if ($staff->role === 'courier') {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'id' => $staff->id,
                    'firstname' => $staff->firstname,
                    'lastname' => $staff->lastname,
                    'email' => $staff->email,
                    'phone_number' => $staff->phone_number,
                    'affiliated_campus' => $staff->campus->name,
                    'remaining_capacity' => $staff->remaining_capacity,
                    'total_picked' => $staff->total_picked,
                    'total_delivered' => $staff->total_delivered,
                    'online' => $staff->online,
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => [
                    'id' => $staff->id,
                    'firstname' => $staff->firstname,
                    'lastname' => $staff->lastname,
                    'email' => $staff->email,
                    'plate_number' => $staff->plate_number,
                    'current_campus' => $staff->currentCampus->name,
                    'remaining_capacity' => $staff->remaining_capacity,
                    'total_unloaded' => $staff->total_unloaded,
                ]
            ]);
        }
    }
}