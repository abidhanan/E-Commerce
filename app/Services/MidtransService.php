<?php

namespace App\Services;

use Midtrans\Snap;

class MidtransService
{
    public function createSnapToken(string $orderCode, int $grossAmount, array $itemDetails, array $customerDetails): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderCode,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        return Snap::getSnapToken($params);
    }
}