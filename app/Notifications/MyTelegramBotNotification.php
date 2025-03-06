<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;

class MyTelegramBotNotification extends Notification
{
    use Queueable;

    public $phone;
    public $product_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($phone, $product_id)
    {
        $this->phone = $phone;
        $this->product_id = $product_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the Telegram representation of the notification.
     */
    public function toTelegram(object $notifiable)
    {
        $product = Book::findOrFail($this->product_id);
        $imageUrl = env('APP_URL') . '/assets/images/isbn/' . $product->image;
        // dd($imageUrl);

        return TelegramFile::create()
            ->content(
                "*Product* : {$product->title} \n" .
                    "*🎉 New Order Received!*\n" .
                    "📞 *Phone:* {$this->phone}\n"
            )
            ->file($imageUrl, 'photo')
            ->to('-4664327715')
        ; // Send the image as a photo
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
