<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaymentLinkNotification extends Notification
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
            ->subject('Link pembayaran untuk '.$order->order_code)
            ->view('Email.order-payment-link', [
                'order' => $order,
                'user' => $notifiable,
                'statusUrl' => route('payments.status', $order->order_code),
            ]);
    }
}
