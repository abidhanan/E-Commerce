<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source' => ['required', 'in:cart,direct'],
            'variant_id' => ['required_if:source,direct', 'nullable', 'exists:product_variants,id'],
            'address_id' => ['required', 'exists:addresses,id'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'selected_items' => ['required_if:source,cart', 'array'],
            'selected_items.*' => [
                'required',
                Rule::exists('cart_items', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }
}