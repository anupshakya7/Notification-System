<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\NotificationSenderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ProcessNotificationJob implements ShouldQueue
{
    use Queueable;

    public $tries = 5;

    public function backoff(): array
    {
        return [5,30,60,300];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(public int $notificationId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationSenderService $sender): void
    {
        $notification = Notification::findOrFail($this->notificationId);

        $notification->update([
            'status' => 'processing'
        ]);

        $sender->send($notification);

        $notification->update([
            'status' => 'processed',
            'processed_at' => now()
        ]);

        Cache::forget('notification_summary');
    }

    public function failed(Throwable $e){
        Notification::whereId($this->notificationId)
            ->update([
                'status' => 'failed',
                'failure_reason' => $e->getMessage()
            ]);
    }
}
