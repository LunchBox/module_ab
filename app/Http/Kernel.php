protected $routeMiddleware = [
    // 其他中介層...
    'auth:client' => \App\Http\Middleware\AuthClient::class,
];