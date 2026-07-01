<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumsikan otorisasi sudah dihandle oleh middleware role
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'images' => 'nullable|array|max:8',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2080',
            'size_guide_id' => 'nullable|exists:size_guides,id',
            'temperature' => 'nullable|integer|min:-10|max:30',
            'intensity' => 'nullable|in:low,high',
            'insulation' => 'nullable|integer',
            'breathability' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'material' => 'nullable|array',
            'material.*' => 'integer|exists:materials,id',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ];
    }
}