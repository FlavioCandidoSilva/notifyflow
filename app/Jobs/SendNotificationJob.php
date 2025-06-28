<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mockery\Matcher\Not;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Notification $notification;

    public WhatsAppService $whatsAppService;

    /**
     * Create a new job instance.
     */
    public function __construct(Notification $notification, WhatsAppService $whatsAppService)
    {
        $this->notification = $notification;
        $this->whatsAppService = $whatsAppService;
    }


    public function handle(): void
    {
        $this->notification->update(['status' => 'processing']);

        $teste = $this->whatsAppService->authenticate();

        $result = $this->whatsAppService->sendMessage(
            '55'. ltrim($this->notification->recipient, '0'),
            $this->notification->message
        );

        if ($result === true) {
            $this->notification->update(['status' => 'sent']);
            Log::info("✅ WhatsApp enviado para {$this->notification->recipient}");
        } else {
            $this->notification->update(['status' => 'failed']);
            Log::error("❌ Falha WhatsApp para {$this->notification->recipient}: $result");
        }
    }

}
