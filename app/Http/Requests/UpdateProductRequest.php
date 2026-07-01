<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan middleware role sudah menjaga gerbang rute ini
    }

    public function rules(): array
    {
        // INI ADALAH HASIL EKSTRAKSI DARI CONTROLLER LAMA-MU
        return [
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'material' => 'nullable|array',
            'size_guide_id' => 'nullable|exists:size_guides,id',
            'images' => 'nullable|array|max:8',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2080',
            'material.*' => 'integer|exists:materials,id',
            'temperature' => 'nullable|integer|min:-10|max:30',
            'intensity' => 'nullable|in:low,high',
            'insulation' => 'nullable|integer|min:0',
            'breathability' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ];
    }
}