<?php

namespace App\Mail\Shop;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Entities\Shop\Comment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class NewCommentToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    private Comment $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Новый комментарий на сайте "Larento"',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.shop.new-comment',
            with: [
                'url' => route('admin.shop.commentaries.index'),
                'textComment' => $this->comment->comment
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
