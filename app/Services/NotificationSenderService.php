<?php
namespace App\Services;

use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Log;

class NotificationSenderService{
    public function send(Notification $notification){
        Log::info('Notification Sent', [
            'id' => $notification->id,
            'type' => $notification->type,
            'recipient' => $notification->recipient,
        ]);

        if(fake()->boolean(20)){
            throw new Exception('Provider timeout');
        }
    }
}
?>