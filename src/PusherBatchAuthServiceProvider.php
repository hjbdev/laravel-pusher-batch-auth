<?php

namespace Hjbdev\LaravelPusherBatchAuth;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PusherBatchAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $app = $this->app;
        Route::macro('pusherBatchAuth', function () use ($app) {
            Route::post('/broadcasting/auth/batch', function (Request $request) use ($app) {
                $broadcastManager = $app->make('Illuminate\Broadcasting\BroadcastManager');
                $pusherBroadcaster = $broadcastManager->connection('pusher');
    
                if (empty($request->channel_name) || !is_array($request->channel_name)) {
                    throw new AccessDeniedHttpException();
                }
    
                $response = [];
    
                foreach ($request->channel_name as $channel) {
                    $req = $request;
                    $req->merge(['channel_name' => $channel]);
    
                    try {
                        $pusherResponse = $pusherBroadcaster->auth($req);
                        $response[$channel] = [
                            'status' => 200,
                            'data' => $pusherResponse
                        ];
                    } catch (AccessDeniedHttpException $e) {
                        $response[$channel] = [
                            'status' => 403,
                        ];
                    }
                }
    
                return $response;
            });
        });
    }
}
