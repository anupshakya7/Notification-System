<?php
namespace App\DTO;

class NotificationDTO{
    public function __construct(
        public string $tenantId,
        public int $userId,
        public string $type,
        public string $recipient,
        public array $payload,
        public ?string $subject = null
    )
    {}
}
?>