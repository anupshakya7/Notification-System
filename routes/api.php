<?php

use App\Http\Controllers\API\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->group(function(){
    //Create Notification
    Route::post('/', [NotificationController::class, 'store']);

    //Get Recent Notifications
    Route::get('/', [NotificationController::class, 'index']);

    //Summary API
    Route::get('/summary', [NotificationController::class, 'summary']);

    //Redis Test
    Route::get('/redis-test', function(){
        Redis::publish('notifications', 'TEST FROM LARAVEL');
        return 'sent';
    });
});
