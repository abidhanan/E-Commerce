<?php

namespace Database\Seeders;

use App\Models\SizeGuide;
use Illuminate\Database\Seeder;

class SizeGuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'type' => 'tops',
                'name' => 'Tops Standard',
                'data' => [
                    'sizes' => [
                        [
                            'size' => 'S',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 86, 'max' => 92, 'unit' => 'cm'],
                                ['label' => 'Length', 'type' => 'simple', 'value' => 66, 'unit' => 'cm'],
                                ['label' => 'Sleeve', 'type' => 'simple', 'value' => 60, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => 'M',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 93, 'max' => 99, 'unit' => 'cm'],
                                ['label' => 'Length', 'type' => 'simple', 'value' => 69, 'unit' => 'cm'],
                                ['label' => 'Sleeve', 'type' => 'simple', 'value' => 62, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => 'L',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 100, 'max' => 108, 'unit' => 'cm'],
                                ['label' => 'Length', 'type' => 'simple', 'value' => 72, 'unit' => 'cm'],
                                ['label' => 'Sleeve', 'type' => 'simple', 'value' => 64, 'unit' => 'cm'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'outerwear',
                'name' => 'Outerwear Standard',
                'data' => [
                    'sizes' => [
                        [
                            'size' => 'S',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 90, 'max' => 96, 'unit' => 'cm'],
                                ['label' => 'Body Length', 'type' => 'simple', 'value' => 68, 'unit' => 'cm'],
                                ['label' => 'Shoulder', 'type' => 'simple', 'value' => 43, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => 'M',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 97, 'max' => 103, 'unit' => 'cm'],
                                ['label' => 'Body Length', 'type' => 'simple', 'value' => 71, 'unit' => 'cm'],
                                ['label' => 'Shoulder', 'type' => 'simple', 'value' => 45, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => 'L',
                            'measurements' => [
                                ['label' => 'Chest', 'type' => 'range', 'min' => 104, 'max' => 112, 'unit' => 'cm'],
                                ['label' => 'Body Length', 'type' => 'simple', 'value' => 74, 'unit' => 'cm'],
                                ['label' => 'Shoulder', 'type' => 'simple', 'value' => 47, 'unit' => 'cm'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'pants',
                'name' => 'Pants Standard',
                'data' => [
                    'sizes' => [
                        [
                            'size' => '30',
                            'measurements' => [
                                ['label' => 'Waist', 'type' => 'range', 'min' => 76, 'max' => 80, 'unit' => 'cm'],
                                ['label' => 'Inseam', 'type' => 'simple', 'value' => 76, 'unit' => 'cm'],
                                ['label' => 'Hip', 'type' => 'simple', 'value' => 96, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => '32',
                            'measurements' => [
                                ['label' => 'Waist', 'type' => 'range', 'min' => 81, 'max' => 85, 'unit' => 'cm'],
                                ['label' => 'Inseam', 'type' => 'simple', 'value' => 78, 'unit' => 'cm'],
                                ['label' => 'Hip', 'type' => 'simple', 'value' => 101, 'unit' => 'cm'],
                            ],
                        ],
                        [
                            'size' => '34',
                            'measurements' => [
                                ['label' => 'Waist', 'type' => 'range', 'min' => 86, 'max' => 90, 'unit' => 'cm'],
                                ['label' => 'Inseam', 'type' => 'simple', 'value' => 80, 'unit' => 'cm'],
                                ['label' => 'Hip', 'type' => 'simple', 'value' => 106, 'unit' => 'cm'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($guides as $guide) {
            SizeGuide::query()->updateOrCreate(
                [
                    'type' => $guide['type'],
                    'name' => $guide['name'],
                ],
                ['data' => $guide['data']],
            );
        }
    }
}
