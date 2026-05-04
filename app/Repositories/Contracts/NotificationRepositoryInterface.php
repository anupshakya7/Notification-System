<?php
namespace App\Repositories\Contracts;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface{
    public function create(array $data): Notification;
    public function updateStatus(int $id, array $data): bool;
    public function findById(int $id): ?Notification;
    public function recent(array $filters): LengthAwarePaginator;
}
?>