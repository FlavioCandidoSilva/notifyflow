<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(Request $request): mixed
    {

        try {
            $data = $request->validate([
                'type' => 'required|in:email,sms,whatsapp',
                'recipient' => 'required|string',
                'message' => 'required|string',
            ]);

            $notification = Notification::create([
                ...$data,
                'status' => 'pending',
            ]);

            SendNotificationJob::dispatch($notification)->onQueue('notifyflow_queue');

            return response()->json([
                'message' => 'Notificação criada e enviada para a fila.',
                'data' => $notification
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar notificação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(): mixed
    {
       return Notification::orderByDesc('id')->get();
    }
}
