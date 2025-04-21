<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageProgress;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function track($tracking_number)
    {
        $package = Package::with(['progresses', 'fromCampus', 'toCampus'])
            ->where('tracking_number', $tracking_number)
            ->first();

        if (!$package) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'from_campus' => $package->fromCampus->name,
                'from_address' => $package->from_address,
                'to_campus' => $package->toCampus->name,
                'to_address' => $package->to_address,
                'status' => $package->status,
                'progress' => $package->progresses->map(function ($progress) {
                    $data = [
                        'status' => $progress->status,
                        'datetime' => $progress->datetime,
                        'returning' => $progress->returning,
                    ];
                    if ($progress->status === 'In transit') {
                        $data['campus'] = $progress->campus->name;
                    }
                    return $data;
                }),
            ]
        ]);
    }

    public function sendPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_campus_id' => 'required|integer|different:to_campus_id|exists:campuses,id',
            'from_address' => 'required|string',
            'to_campus_id' => 'required|integer|different:from_campus_id|exists:campuses,id',
            'to_address' => 'required|string',
            'recipient_name' => 'required',
            'recipient_phone_number' => 'required|min:8|max:12',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('from_campus_id') || $validator->errors()->has('to_campus_id')) {
                if ($request->from_campus_id == $request->to_campus_id) {
                    return response()->json(['message' => 'cannot send the package to the same campus'], 422);
                }
                return response()->json(['message' => 'campus does not exist'], 422);
            }
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        // 随机分配快递员
        $courier = Staff::where('role', 'courier')
            ->where('campus_id', $request->from_campus_id)
            ->where('online', true)
            ->inRandomOrder()
            ->first();

        if (!$courier) {
            return response()->json(['message' => 'no available couriers in the campus'], 409);
        }

        // 生成运单号
        $trackingNumber = 'CE' . 
                         $request->from_campus_id . 
                         $request->to_campus_id . 
                         now()->format('Ymd') . 
                         str_pad(Package::count() + 1, 3, '0', STR_PAD_LEFT);

        $package = Package::create([
            'tracking_number' => $trackingNumber,
            'client_id' => $request->user()->id,
            'from_campus_id' => $request->from_campus_id,
            'from_address' => $request->from_address,
            'to_campus_id' => $request->to_campus_id,
            'to_address' => $request->to_address,
            'recipient_name' => $request->recipient_name,
            'recipient_phone_number' => $request->recipient_phone_number,
            'status' => 'Pending pickup',
            'pickup_courier_id' => $courier->id,
        ]);

        PackageProgress::create([
            'package_id' => $package->id,
            'status' => 'Pending pickup',
            'datetime' => now(),
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $package->id,
                'courier_name' => $courier->firstname . ' ' . $courier->lastname,
                'courier_phone_number' => $courier->phone_number,
            ]
        ], 201);
    }

    public function getMyPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus'])
            ->where('client_id', $request->user()->id)
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    'tracking_number' => $package->tracking_number,
                    'from_campus' => $package->fromCampus->name,
                    'from_address' => $package->from_address,
                    'to_campus' => $package->toCampus->name,
                    'to_address' => $package->to_address,
                    'status' => $package->status,
                    'returning' => $package->returning,
                    'send_time' => $package->created_at->format('Y-m-d\TH:i:s.v\Z'),
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ]
                ];
            })
        ]);
    }

    public function returnPackage(Request $request, $package_id)
    {
        $package = Package::find($package_id);
        
        if (!$package) {
            return response()->json(['message' => 'not found'], 404);
        }

        if ($package->client_id != $request->user()->id) {
            return response()->json(['message' => 'you cannot return other client\'s packages'], 403);
        }

        if ($package->status === 'Signed') {
            return response()->json(['message' => 'cannot return a signed package'], 422);
        }

        $newStatus = '';
        switch ($package->status) {
            case 'Pending pickup':
                $newStatus = 'Signed';
                break;
            case 'Picked up':
                $newStatus = 'Delivering';
                break;
            case 'Pending delivery':
                $newStatus = 'Pending transit';
                break;
            case 'Delivering':
                $newStatus = 'Picked up';
                break;
            default:
                return response()->json(['message' => 'cannot return package in current status'], 422);
        }

        $package->update([
            'status' => $newStatus,
            'returning' => 1,
        ]);

        PackageProgress::create([
            'package_id' => $package->id,
            'status' => $newStatus,
            'datetime' => now(),
            'returning' => 1,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $package->id,
                'status' => $newStatus,
            ]
        ]);
    }
}