<?php
namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

class NotificationSummaryService{
    private const CACHE_KEY = 'notification_summary';

    public function getSummary(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(5), function(){
            return $this->buildSummary();
        }); 
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function buildSummary(): array
    {
        return [
            'total' => Notification::count(),
            'processed' => Notification::where('status', 'processed')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
            'pending' => Notification::where('status', 'pending')->count()
        ];
    }
}
?>