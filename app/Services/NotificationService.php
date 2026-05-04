<?php
namespace App\Services;

use App\DTO\NotificationDTO;
use App\Jobs\ProcessNotificationJob;
use Exception;
use NotificationRepositoryInterface;

class NotificationService{
    public function __construct(
        private NotificationRepositoryInterface $repo,
        private RateLimitService $limiter
    ){}

    public function create(NotificationDTO $dto){
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
        return $notification;
    }
}
?>