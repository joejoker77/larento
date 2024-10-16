<?php

namespace App\Listeners;


use App\Events\NewComment;
use App\Mail\Shop\NewCommentToAdmin;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class NewCommentCreated implements ShouldQueueAfterCommit
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


    public function handle(NewComment $event):void
    {
        $this->mailer->to(env('MAIL_FROM_ADDRESS'))->send(new NewCommentToAdmin($event->comment));
    }
}
