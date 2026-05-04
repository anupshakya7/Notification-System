<?php
namespace App\Services;

use Illuminate\Support\Facades\RateLimiter;

class RateLimitService{
    public function check(string $tenantId, int $userId): bool
    {
        $key = "notify:{$tenantId}:{$userId}";

        if(RateLimiter::tooManyAttempts($key, 10)){
            return false;
        }

        RateLimiter::hit($key, 3600);
        return true;
    }
}
?>