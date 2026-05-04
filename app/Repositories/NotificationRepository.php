<?php
namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class NotificationRepository implements NotificationRepositoryInterface{
    public function create(array $data): Notification{
        return Notification::create($data);
    }

    public function updateStatus(int $id, array $data): bool{
        $updated = Notification::where('id', $id)->update($data) > 0;

        if($updated){
            Cache::forget('notification_summary');
        }

        return $updated;
    }

    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    public function recent(array $filters): LengthAwarePaginator{
        return Notification::query()
            ->when($filters['status'] ?? null,
                fn($q,$status) => $q->whereStatus($status))
            ->when($filters['tenant_id'] ?? null, function($query, $tenantId){
                $query->where('tenant_id', $tenantId);
            })
            ->when($filters['user_id'] ?? null, function($query, $userId){
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(20);
    }
}

?>