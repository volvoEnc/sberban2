<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class PushNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title("Что то случилось!")
            ->body($this->getCurrentDate());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    protected function getCurrentDate(): string
    {
        return date('d') . self::parseMonth('n') . date('Y H:i:s');
    }

    protected static function parseMonth($month): ?string
    {
        return [
            null,
            'января','февраля','марта',
            'апреля','мая','июня',
            'июля','августа','сентября',
            'октября','ноября','декабря'
        ][$month];
    }

}
