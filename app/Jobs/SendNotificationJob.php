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

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Notification $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }


    public function handle(): void
    {
        $this->notification->update(['status' => 'processing']);

        Log::info("🔔 Enviando {$this->notification->type} para {$this->notification->recipient}");

        sleep(2);

        $this->notification->update(['status' => 'sent']);

        Log::info("✅ {$this->notification->type} enviado com sucesso para {$this->notification->recipient}");

    }

}
