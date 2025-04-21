<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Package;
use App\Models\PackageProgress;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourierController extends Controller
{
    public function onlineToggle(Request $request)
    {
        $courier = $request->user();
        $courier->update([
            'online' => !$courier->online,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $courier->id,
                'online' => $courier->online,
            ]
        ]);
    }

    public function getPendingPickupPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus', 'client'])
            ->where('status', 'Pending pickup')
            ->where('pickup_courier_id', $request->user()->id)
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
                    'sender' => [
                        'id' => $package->client->id,
                        'firstname' => $package->client->firstname,
                        'lastname' => $package->client->lastname,
                        'phone_number' => $package->client->phone_number,
                    ],
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ]
                ];
            })
        ]);
    }

    public function carryPendingPickupPackages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id_list' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $courier = $request->user();
        $validPackages = [];
        
        foreach ($request->package_id_list as $packageId) {
            $package = Package::find($packageId);
            
            if ($package && 
                $package->status === 'Pending pickup' && 
                $package->pickup_courier_id === $courier->id &&
                count($validPackages) < $courier->remaining_capacity) {
                
                $package->update(['status' => 'Picked up']);
                PackageProgress::create([
                    'package_id' => $package->id,
                    'status' => 'Picked up',
                    'datetime' => now(),
                ]);
                
                $validPackages[] = $package->id;
            }
        }

        if (count($validPackages)) {
            $courier->increment('total_picked', count($validPackages));
            $courier->decrement('remaining_capacity', count($validPackages));
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'package_id_list' => $validPackages,
            ]
        ]);
    }

    public function getPendingDeliveryPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus', 'client'])
            ->where('status', 'Pending delivery')
            ->where('to_campus_id', $request->user()->campus_id)
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
                    'sender' => [
                        'id' => $package->client->id,
                        'firstname' => $package->client->firstname,
                        'lastname' => $package->client->lastname,
                        'phone_number' => $package->client->phone_number,
                    ],
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ]
                ];
            })
        ]);
    }

    public function carryPendingDeliveryPackages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id_list' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $courier = $request->user();
        $validPackages = [];
        
        foreach ($request->package_id_list as $packageId) {
            $package = Package::find($packageId);
            
            if ($package && 
                $package->status === 'Pending delivery' && 
                $package->to_campus_id === $courier->campus_id &&
                count($validPackages) < $courier->remaining_capacity) {
                
                $package->update(['status' => 'Delivering', 'delivery_courier_id' => $courier->id]);
                PackageProgress::create([
                    'package_id' => $package->id,
                    'status' => 'Delivering',
                    'datetime' => now(),
                ]);
                
                $validPackages[] = $package->id;
            }
        }

        if (count($validPackages)) {
            $courier->decrement('remaining_capacity', count($validPackages));
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'package_id_list' => $validPackages,
            ]
        ]);
    }

    public function getPickedUpPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus', 'client'])
            ->where('status', 'Picked up')
            ->where('pickup_courier_id', $request->user()->id)
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
                    'sender' => [
                        'id' => $package->client->id,
                        'firstname' => $package->client->firstname,
                        'lastname' => $package->client->lastname,
                        'phone_number' => $package->client->phone_number,
                    ],
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ]
                ];
            })
        ]);
    }

    public function packPackages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id_list' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $courier = $request->user();
        $validPackages = [];
        $containerIds = [];
        
        foreach ($request->package_id_list as $packageId) {
            $package = Package::find($packageId);
            
            if ($package && 
                $package->status === 'Picked up' && 
                $package->pickup_courier_id === $courier->id) {
                
                // 查找合适的集装箱
                $container = Container::where('from_campus_id', $package->from_campus_id)
                    ->where('to_campus_id', $package->to_campus_id)
                    ->where('status', 'waiting')
                    ->whereHas('packages', function($query) {
                        $query->where('status', 'Pending transit');
                    }, '<', 5)
                    ->first();
                
                if (!$container) {
                    $container = Container::create([
                        'from_campus_id' => $package->from_campus_id,
                        'to_campus_id' => $package->to_campus_id,
                        'status' => 'waiting',
                    ]);
                }
                
                $package->update([
                    'status' => 'Pending transit',
                    'container_id' => $container->id,
                ]);
                
                PackageProgress::create([
                    'package_id' => $package->id,
                    'status' => 'Pending transit',
                    'datetime' => now(),
                    'returning' => $package->returning,
                ]);
                
                $validPackages[] = $package->id;
                $containerIds[$container->id] = true;
            }
        }

        $courier->increment('remaining_capacity', count($validPackages));

        return response()->json([
            'message' => 'success',
            'data' => [
                'container_id_list' => array_keys($containerIds),
                'package_id_list' => $validPackages,
            ]
        ]);
    }

    public function getDeliveringPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus', 'client'])
            ->where('status', 'Delivering')
            ->where('delivery_courier_id', $request->user()->id)
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
                    'sender' => [
                        'id' => $package->client->id,
                        'firstname' => $package->client->firstname,
                        'lastname' => $package->client->lastname,
                        'phone_number' => $package->client->phone_number,
                    ],
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ]
                ];
            })
        ]);
    }

    public function deliveredPackage(Request $request, $package_id)
    {
        $package = Package::find($package_id);
        
        if (!$package || 
            $package->status !== 'Delivering' || 
            $package->delivery_courier_id !== $request->user()->id) {
            return response()->json(['message' => 'not found'], 404);
        }

        $package->update(['status' => 'Signed']);
        PackageProgress::create([
            'package_id' => $package->id,
            'status' => 'Signed',
            'datetime' => now(),
            'returning' => $package->returning,
        ]);

        $courier = $request->user();
        $courier->increment('total_delivered');
        $courier->increment('remaining_capacity');

        return response()->json(['message' => 'success']);
    }

    public function getDeliveredPackages(Request $request)
    {
        $packages = Package::with(['fromCampus', 'toCampus', 'client'])
            ->where('status', 'Signed')
            ->where('delivery_courier_id', $request->user()->id)
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
                    'sender' => [
                        'id' => $package->client->id,
                        'firstname' => $package->client->firstname,
                        'lastname' => $package->client->lastname,
                        'phone_number' => $package->client->phone_number,
                    ],
                    'recipient' => [
                        'name' => $package->recipient_name,
                        'phone_number' => $package->recipient_phone_number,
                    ],
                    'delivered_time' => $package->updated_at->format('Y-m-d\TH:i:s.v\Z'),
                ];
            })
        ]);
    }
}