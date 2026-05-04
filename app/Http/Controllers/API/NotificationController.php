<?php

namespace App\Http\Controllers\API;

use App\DTO\NotificationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Services\NotificationService;
use App\Services\NotificationSummaryService;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $service,
        private NotificationRepositoryInterface $repo,
        private NotificationSummaryService $summaryService
    ){}

    public function store(StoreNotificationRequest $request){
        $data = $request->validated();
        $dto = new NotificationDTO(
            tenantId: $data['tenant_id'],
            userId: $data['user_id'],
            type: $data['type'],
            recipient: $data['recipient'],
            subject: $data['subject'],
            payload: $data['payload'],
        );

        $notification = $this->service->create($dto);

        return response()->json([
            'message' => 'Notification Queued',
            'data' => $notification
        ], 201);
    }

    public function index(Request $request){
        return response()->json(
            $this->repo->recent($request->all())
        );
    }

    public function summary(){
        return response()->json(
            $this->summaryService->getSummary()
        );
    }
}
