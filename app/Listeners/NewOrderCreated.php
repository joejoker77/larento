<?php

namespace App\Listeners;

use App\Events\NewOrder;
use App\Mail\Shop\NewOrderToAdmin;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class NewOrderCreated implements ShouldQueueAfterCommit
{

    use InteractsWithQueue;

    private Mailer $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param NewOrder $event
     * @return void
     */
    public function handle(NewOrder $event):void
    {
        $this->mailer->to(env('MAIL_FROM_ADDRESS'))->send(new NewOrderToAdmin($event->order));
    }
}
