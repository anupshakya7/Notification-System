<?php

namespace Tests\Feature;

use App\Jobs\ProcessNotificationJob;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function it_creates_notification_and_dispatches_job(){
        Queue::fake();

        $payload = [
            'tenant_id' => 1,
            'user_id' => 1,
            'type' => 'email',
            'recipient' => 'test@example.com',
            'subject' => 'Test',
            'payload' => ['message' => 'hello']
        ];

        $response = $this->postJson('/api/notifications', $payload);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => ['id', 'tenant_id', 'user_id']
                ]);
        
        $this->assertDatabaseHas('notifications', [
            'tenant_id' => 1,
            'user_id' => 1
        ]);

        Queue::assertPushed(ProcessNotificationJob::class);
    }

    public function it_blocks_after_10_requests_per_hour(){
        for($i=0; $i<10; $i++){
            $this->postJson('/api/notifications',[
                'tenant_id' => 1,
                'user_id' => 1,
                'type' => 'email',
                'recipient' => 'test@example.com',
                'subject' => 'Test',
                'payload' => [],
            ]);
        }

        $response = $this->postJson('/api/notifications', [
            'tenant_id' => 1,
            'user_id' => 1,
            'type' => 'email',
            'recipient' => 'test@example.com',
            'subject' => 'Test',
            'payload' => [],
        ]);

        $response->assertStatus(500);
    }

    public function it_returns_notification_summary(){
        Notification::factory()->count(3)->create(['status' => 'processed']);
        Notification::factory()->count(2)->create(['status' => 'failed']);

        $response = $this->getJson('/api/notifications/summary');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'total',
                    'processed',
                    'failed',
                    'pending'
                ]);
    }
}
