<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Package;
use App\Models\PackageProgress;
use App\Models\Staff;
use App\Models\RouteCampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TruckerController extends Controller
{
    public function getMyContainers(Request $request)
    {
        $containers = Container::with(['fromCampus', 'toCampus'])
            ->where('trucker_id', $request->user()->id)
            ->where('status', 'loaded')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $containers->map(function ($container) {
                return [
                    'id' => $container->id,
                    'from_campus' => $container->fromCampus->name,
                    'to_campus' => $container->toCampus->name,
                    'packages' => $container->packages()->count(),
                ];
            })
        ]);
    }

    public function getCurrentCampusContainers(Request $request)
    {
        $trucker = $request->user();
        $containers = Container::with(['fromCampus', 'toCampus'])
            ->where(function($query) use ($trucker) {
                $query->where('from_campus_id', $trucker->campus_id)
                      ->orWhere('to_campus_id', $trucker->campus_id);
            })
            ->where('status', 'waiting')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $containers->map(function ($container) {
                return [
                    'id' => $container->id,
                    'from_campus' => $container->fromCampus->name,
                    'to_campus' => $container->toCampus->name,
                    'packages' => $container->packages()->count(),
                ];
            })
        ]);
    }

    public function loadContainer(Request $request, $container_id)
    {
        $container = Container::find($container_id);
        $trucker = $request->user();
        
        if (!$container || 
            $container->from_campus_id !== $trucker->campus_id || 
            $container->status !== 'waiting') {
            return response()->json(['message' => 'not found'], 404);
        }

        $packageCount = $container->packages()->count();
        if ($packageCount > $trucker->remaining_capacity) {
            return response()->json([
                'message' => 'over the trucker\'s remaining capacity',
                'data' => [
                    'remaining_capacity' => $trucker->remaining_capacity
                ]
            ], 409);
        }

        $container->update([
            'status' => 'loaded',
            'trucker_id' => $trucker->id,
        ]);

        $trucker->decrement('remaining_capacity', $packageCount);

        return response()->json(['message' => 'success']);
    }

    public function unloadContainer(Request $request, $container_id)
    {
        $container = Container::find($container_id);
        $trucker = $request->user();
        
        if (!$container || 
            $container->to_campus_id !== $trucker->campus_id || 
            $container->status !== 'loaded' ||
            $container->trucker_id !== $trucker->id) {
            return response()->json(['message' => 'not found'], 404);
        }

        $returnPackages = [];
        foreach ($container->packages as $package) {
            if ($package->returning) {
                // 创建返回包裹
                $newPackage = Package::create([
                    'tracking_number' => 'CE' . $package->to_campus_id . $package->from_campus_id . now()->format('Ymd') . str_pad(Package::count() + 1, 3, '0', STR_PAD_LEFT),
                    'client_id' => $package->client_id,
                    'from_campus_id' => $package->to_campus_id,
                    'from_address' => $package->to_address,
                    'to_campus_id' => $package->from_campus_id,
                    'to_address' => $package->from_address,
                    'recipient_name' => $package->client->firstname . ' ' . $package->client->lastname,
                    'recipient_phone_number' => $package->client->phone_number,
                    'status' => 'Pending delivery',
                    'returning' => 0,
                ]);

                PackageProgress::create([
                    'package_id' => $newPackage->id,
                    'status' => 'Pending delivery',
                    'datetime' => now(),
                ]);

                $returnPackages[] = [
                    'package_id' => $newPackage->id,
                    'container_id' => $container->id,
                ];
            } else {
                $package->update(['status' => 'Pending delivery']);
                PackageProgress::create([
                    'package_id' => $package->id,
                    'status' => 'Pending delivery',
                    'datetime' => now(),
                ]);
            }
        }

        $container->update([
            'status' => 'unloaded',
            'trucker_id' => null,
        ]);

        $trucker->increment('remaining_capacity', $container->packages()->count());
        $trucker->increment('total_unloaded');

        return response()->json([
            'message' => 'success',
            'data' => [
                'return_packages' => $returnPackages,
            ]
        ]);
    }

    public function getMyRoute(Request $request)
    {
        $trucker = $request->user();
        $routeId = $trucker->route_id;
        $currentCampusId = $trucker->campus_id;

        // 获取路线上的所有园区及其顺序
        $routeCampuses = RouteCampus::with('campus')
            ->where('route_id', $routeId)
            ->orderBy('order')
            ->get();

        // 格式化响应数据
        $routeData = $routeCampuses->map(function ($routeCampus) use ($currentCampusId) {
            return [
                'id' => $routeCampus->campus_id,
                'order' => $routeCampus->order,
                'name' => $routeCampus->campus->name,
                'current' => $routeCampus->campus_id == $currentCampusId ? 1 : 0,
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $routeData
        ]);
    }

    public function nextCampus(Request $request)
    {
        $trucker = $request->user();
        $routeId = $trucker->route_id;
        $currentCampusId = $trucker->campus_id;

        // 获取当前园区在路线中的顺序
        $currentOrder = RouteCampus::where('route_id', $routeId)
            ->where('campus_id', $currentCampusId)
            ->value('order');

        if ($currentOrder === null) {
            return response()->json(['message' => 'invalid current campus'], 422);
        }

        // 获取下一个园区
        $nextOrder = $currentOrder + 1;
        
        // 检查是否是路线的最后一个园区
        $maxOrder = RouteCampus::where('route_id', $routeId)->max('order');
        
        if ($nextOrder > $maxOrder) {
            $nextOrder = 1; // 环形路线，回到第一个园区
        }

        $nextCampus = RouteCampus::with('campus')
            ->where('route_id', $routeId)
            ->where('order', $nextOrder)
            ->first();

        if (!$nextCampus) {
            return response()->json(['message' => 'route configuration error'], 500);
        }

        // 更新卡车司机的当前园区
        $trucker->update([
            'campus_id' => $nextCampus->campus_id,
            'remaining_capacity' => 15, // 重置容量
        ]);

        // 更新所有装载的集装箱状态
        Container::where('trucker_id', $trucker->id)
            ->where('status', 'loaded')
            ->update([
                'status' => 'in transit'
            ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'campus' => $nextCampus->campus->name
            ]
        ]);
    }

    // 优化后的 getPackagesInContainer 方法
    public function getPackagesInContainer(Request $request, $container_id)
    {
        $container = Container::with(['packages.client', 'packages.progresses'])
            ->find($container_id);
        
        if (!$container) {
            return response()->json(['message' => 'not found'], 404);
        }

        $packages = $container->packages->map(function ($package) {
            return [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
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
                'status' => $package->status,
                'returning' => $package->returning,
                'progress' => $package->progresses->map(function ($progress) {
                    return [
                        'status' => $progress->status,
                        'datetime' => $progress->datetime,
                        'returning' => $progress->returning,
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $packages
        ]);
    }
}