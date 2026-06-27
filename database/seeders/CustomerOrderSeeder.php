<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::query()->where('email', 'customer@toko.com')->first();

        if (! $customer) {
            return;
        }

        $addresses = [
            [
                'label' => 'Rumah',
                'recipient_name' => 'Customer',
                'phone_number' => '081100000006',
                'full_address' => 'Jl. Melati No. 12, Kebayoran Baru',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12120',
                'note' => 'Titip ke security jika tidak ada orang di rumah.',
                'latitude' => -6.2443710,
                'longitude' => 106.7990720,
                'is_primary' => true,
            ],
            [
                'label' => 'Kantor',
                'recipient_name' => 'Customer',
                'phone_number' => '081100000006',
                'full_address' => 'Jl. Jenderal Sudirman Kav. 52',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
                'note' => 'Antar pada jam kerja.',
                'latitude' => -6.2246290,
                'longitude' => 106.8090390,
                'is_primary' => false,
            ],
        ];

        foreach ($addresses as $address) {
            Address::query()->updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'label' => $address['label'],
                ],
                $address,
            );
        }

        $orders = [
            [
                'order_code' => 'ORD-DUMMY-0001',
                'status' => 'paid',
                'payment_status' => 'success',
                'items' => [
                    ['sku' => 'ASH-JKT-M', 'qty' => 1],
                    ['sku' => 'TRL-TEE-M', 'qty' => 2],
                ],
            ],
            [
                'order_code' => 'ORD-DUMMY-0002',
                'status' => 'pending',
                'payment_status' => 'pending',
                'items' => [
                    ['sku' => 'CMT-WDB-S', 'qty' => 1],
                ],
            ],
            [
                'order_code' => 'ORD-DUMMY-0003',
                'status' => 'failed',
                'payment_status' => 'failed',
                'items' => [
                    ['sku' => 'RDG-PNT-32', 'qty' => 1],
                    ['sku' => 'DLY-MRN-M', 'qty' => 1],
                ],
            ],
        ];

        foreach ($orders as $item) {
            $variants = collect($item['items'])
                ->map(function (array $line) {
                    $variant = ProductVariant::query()
                        ->with('product')
                        ->where('sku', $line['sku'])
                        ->first();

                    if (! $variant) {
                        return null;
                    }

                    return [
                        'variant' => $variant,
                        'qty' => $line['qty'],
                    ];
                })
                ->filter()
                ->values();

            if ($variants->isEmpty()) {
                continue;
            }

            $grossAmount = $variants->sum(
                fn (array $line) => (float) $line['variant']->price * $line['qty']
            );
            $shippingCost = match ($item['status']) {
                'paid' => 24000,
                'pending' => 18000,
                'failed' => null,
                default => 20000,
            };
            $addressId = $customer->addresses()->orderByDesc('is_primary')->value('id');

            $order = Order::query()->updateOrCreate(
                ['order_code' => $item['order_code']],
                [
                    'user_id' => $customer->id,
                    'address_id' => $addressId,
                    'subtotal' => $grossAmount,
                    'shipping_cost' => $shippingCost,
                    'gross_amount' => $grossAmount + ($shippingCost ?? 0),
                    'status' => $item['status'],
                    'payment_gateway' => 'duitku',
                    'payment_reference' => $item['status'] === 'failed' ? null : 'DUITKU-' . $item['order_code'],
                    'payment_method' => config('duitku.payment_method', 'VC'),
                    'payment_status' => $item['payment_status'],
                    'payment_url' => $item['status'] === 'failed'
                        ? null
                        : 'https://sandbox.duitku.com/checkout/' . strtolower($item['order_code']),
                    'paid_at' => $item['status'] === 'paid' ? now()->subDays(2) : null,
                    'customer_note' => 'Mohon diproses sesuai alamat terpilih.',
                    'quoted_at' => in_array($item['status'], ['paid', 'pending'], true) ? now()->subDays(2) : null,
                ],
            );

            foreach ($variants as $line) {
                $variant = $line['variant'];

                OrderItem::query()->updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                    ],
                    [
                        'product_id' => $variant->product_id,
                        'price' => $variant->price,
                        'qty' => $line['qty'],
                    ],
                );
            }
        }
    }
}
