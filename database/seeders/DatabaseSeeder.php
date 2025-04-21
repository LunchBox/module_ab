<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 创建园区
        $campuses = [
            ['id' => 1, 'name' => 'Campus A'],
            ['id' => 2, 'name' => 'Campus B'],
            ['id' => 3, 'name' => 'Campus C'],
            ['id' => 4, 'name' => 'Campus D'],
            ['id' => 5, 'name' => 'Campus E'],
        ];

        foreach ($campuses as $campus) {
            \App\Models\Campus::create($campus);
        }
        
        // 创建路线
        $routes = [
            ['id' => 1, 'name' => 'Route 1'],
            ['id' => 2, 'name' => 'Route 2'],
        ];
        
        foreach ($routes as $route) {
            \App\Models\Route::create($route);
        }
        
        // 创建路线园区关联 (路线1: A->B->C->D->E->A...)
        $route1Order = [
            ['route_id' => 1, 'campus_id' => 1, 'order' => 1],
            ['route_id' => 1, 'campus_id' => 2, 'order' => 2],
            ['route_id' => 1, 'campus_id' => 3, 'order' => 3],
            ['route_id' => 1, 'campus_id' => 4, 'order' => 4],
            ['route_id' => 1, 'campus_id' => 5, 'order' => 5],
        ];
        
        // 路线2: E->D->C->B->A->E...
        $route2Order = [
            ['route_id' => 2, 'campus_id' => 5, 'order' => 1],
            ['route_id' => 2, 'campus_id' => 4, 'order' => 2],
            ['route_id' => 2, 'campus_id' => 3, 'order' => 3],
            ['route_id' => 2, 'campus_id' => 2, 'order' => 4],
            ['route_id' => 2, 'campus_id' => 1, 'order' => 5],
        ];
        
        foreach (array_merge($route1Order, $route2Order) as $order) {
            \App\Models\RouteCampus::create($order);
        }
        
        // 创建管理员
        \App\Models\User::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ]);
        
        // 创建客户
        $clients = [
            [
                'email' => 'client@wsc.com',
                'password' => Hash::make('client'),
                'firstname' => 'John',
                'lastname' => 'Doe',
                'phone_number' => '11890481',
            ],
            [
                'email' => 'smith@wsc.com',
                'password' => Hash::make('smith'),
                'firstname' => 'Mary',
                'lastname' => 'Smith',
                'phone_number' => '33178732',
            ],
        ];
        
        foreach ($clients as $client) {
            \App\Models\Client::create($client);
        }
        
        // 创建快递员
        $couriers = [
            [
                'email' => 'courier@wsc.com',
                'password' => Hash::make('courier'),
                'firstname' => 'Matthew',
                'lastname' => 'Green',
                'phone_number' => '12890482',
                'role' => 'courier',
                'campus_id' => 1,
                'remaining_capacity' => 5,
            ],
            [
                'email' => 'a_courier@wsc.com',
                'password' => Hash::make('courier_a'),
                'firstname' => 'David',
                'lastname' => 'Hill',
                'phone_number' => '33178732',
                'role' => 'courier',
                'campus_id' => 1,
                'remaining_capacity' => 5,
            ],
            [
                'email' => 'b_courier@wsc.com',
                'password' => Hash::make('courier_b'),
                'firstname' => 'Sarah',
                'lastname' => 'Allen',
                'phone_number' => '89471648',
                'role' => 'courier',
                'campus_id' => 2,
                'remaining_capacity' => 5,
            ],
            [
                'email' => 'c_courier@wsc.com',
                'password' => Hash::make('courier_c'),
                'firstname' => 'James',
                'lastname' => 'Lewis',
                'phone_number' => '38475913',
                'role' => 'courier',
                'campus_id' => 3,
                'remaining_capacity' => 5,
            ],
            [
                'email' => 'd_courier@wsc.com',
                'password' => Hash::make('courier_d'),
                'firstname' => 'Daniel',
                'lastname' => 'King',
                'phone_number' => '67846378',
                'role' => 'courier',
                'campus_id' => 4,
                'remaining_capacity' => 5,
            ],
            [
                'email' => 'e_courier@wsc.com',
                'password' => Hash::make('courier_e'),
                'firstname' => 'Joseph',
                'lastname' => 'Phi',
                'phone_number' => '17264527',
                'role' => 'courier',
                'campus_id' => 5,
                'remaining_capacity' => 5,
            ],

            // 添加其他快递员...
        ];
        
        foreach ($couriers as $courier) {
            \App\Models\Staff::create($courier);
        }
        
        // 创建卡车司机
        $truckers = [
            [
                'email' => 'trucker@wsc.com',
                'password' => Hash::make('trucker'),
                'firstname' => 'Harper',
                'lastname' => 'Lopez',
                'role' => 'trucker',
                'plate_number' => 'A10001',
                'route_id' => 1,
                'remaining_capacity' => 15,
            ],
            [
                'email' => 'trucker1@wsc.com',
                'password' => Hash::make('trucker1'),
                'firstname' => 'Samuel',
                'lastname' => 'Nelson',
                'role' => 'trucker',
                'plate_number' => 'A10002',
                'route_id' => 1,
                'remaining_capacity' => 15,
            ],
            [
                'email' => 'trucker2_1@wsc.com',
                'password' => Hash::make('trucker2'),
                'firstname' => 'Grace',
                'lastname' => 'Adams',
                'role' => 'trucker',
                'plate_number' => 'A10003',
                'route_id' => 2,
                'remaining_capacity' => 15,
            ],
            [
                'email' => 'trucker2_2@wsc.com',
                'password' => Hash::make('trucker3'),
                'firstname' => 'Mia',
                'lastname' => 'Mitchell',
                'role' => 'trucker',
                'plate_number' => 'A10004',
                'route_id' => 2,
                'remaining_capacity' => 15,
            ],
            // 添加其他卡车司机...
        ];
        
        foreach ($truckers as $trucker) {
            \App\Models\Staff::create($trucker);
        }
    }
}
