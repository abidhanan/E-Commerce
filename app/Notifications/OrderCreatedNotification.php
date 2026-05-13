<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    public function __construct(private readonly Order $order) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $order = $this->order->loadMissing(['address', 'items.product', 'items.productVariant']);

        return (new MailMessage)
            ->subject('Pesanan '.$order->order_code.' berhasil dibuat')
            ->view('Email.order-created', [
                'order' => $order,
                'user' => $notifiable,
                'statusUrl' => route('payments.status', $order->order_code),
            ]);
    }
}
