<?php
namespace App\Services;

use App\DTO\NotificationDTO;
use App\Events\NotificationCreated;
use App\Jobs\ProcessNotificationJob;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class NotificationService{
    public function __construct(
        private NotificationRepositoryInterface $repo,
        private RateLimitService $limiter
    ){}

    public function create(NotificationDTO $dto){
        Log::info('SERVICE HIT');
        if(!$this->limiter->check($dto->tenantId, $dto->userId)){
            throw new Exception('Rate limit exceeded');
        }

        $notification = $this->repo->create([
            'tenant_id' => $dto->tenantId,
            'user_id' => $dto->userId,
            'type' => $dto->type,
            'recipient' => $dto->recipient,
            'subject' => $dto->subject,
            'payload' => $dto->payload
        ]);

        ProcessNotificationJob::dispatch($notification->id);

        Log::info('About to publish redis');
        event(new NotificationCreated($notification));
        return $notification;
    }
}
?>