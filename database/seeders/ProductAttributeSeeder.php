<?php

namespace Database\Seeders;

use App\Models\Breathability;
use App\Models\Insulation;
use App\Models\Intensities;
use App\Models\Material;
use App\Models\TemperatureProduct;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $temperatures = [
            [
                'min_temperature' => -10,
                'max_temperature' => 0,
                'label' => 'Cold Weather',
                'description' => 'Untuk cuaca sangat dingin, camping dataran tinggi, atau perjalanan malam.',
            ],
            [
                'min_temperature' => 1,
                'max_temperature' => 10,
                'label' => 'Cool Weather',
                'description' => 'Cocok untuk udara sejuk, hiking pagi, dan layering ringan.',
            ],
            [
                'min_temperature' => 11,
                'max_temperature' => 15,
                'label' => 'Mild Weather',
                'description' => 'Nyaman untuk aktivitas harian, commuting, dan outdoor ringan.',
            ],
            [
                'min_temperature' => 16,
                'max_temperature' => 20,
                'label' => 'Comfort Weather',
                'description' => 'Ideal untuk mobilitas harian dan aktivitas luar ruang dengan suhu yang cukup nyaman.',
            ],
            [
                'min_temperature' => 21,
                'max_temperature' => 25,
                'label' => 'Warm Weather',
                'description' => 'Cocok untuk cuaca hangat dengan kebutuhan sirkulasi udara yang tetap baik.',
            ],
            [
                'min_temperature' => 26,
                'max_temperature' => 30,
                'label' => 'Hot Weather',
                'description' => 'Bahan ringan untuk cuaca panas dan aktivitas yang butuh sirkulasi udara.',
            ],
        ];

        foreach ($temperatures as $temperature) {
            TemperatureProduct::query()->updateOrCreate(
                ['label' => $temperature['label']],
                $temperature,
            );
        }

        $intensities = [
            [
                'label' => 'low',
                'description' => 'Aktivitas santai seperti commuting, jalan kota, dan pemakaian harian.',
            ],
            [
                'label' => 'high',
                'description' => 'Aktivitas intens seperti trail run, hiking cepat, dan perjalanan panjang.',
            ],
        ];

        foreach ($intensities as $intensity) {
            Intensities::query()->updateOrCreate(
                ['label' => $intensity['label']],
                $intensity,
            );
        }

        $insulations = [
            [
                'level' => 0,
                'label' => '0/6',
                'description' => 'No insulation. Designed purely for warm conditions - heat retention is not a priority.',
            ],
            [
                'level' => 1,
                'label' => '1/6',
                'description' => 'Minimal insulation. Best suited for warm to hot conditions with little need for warmth retention.',
            ],
            [
                'level' => 2,
                'label' => '2/6',
                'description' => 'Light insulation. Comfortable in mild conditions where a small amount of warmth is beneficial.',
            ],
            [
                'level' => 3,
                'label' => '3/6',
                'description' => 'Moderate insulation. Balanced warmth for transitional weather - neither too warm nor too cold.',
            ],
            [
                'level' => 4,
                'label' => '4/6',
                'description' => 'Good insulation. Provides reliable warmth for cool to cold conditions.',
            ],
            [
                'level' => 5,
                'label' => '5/6',
                'description' => 'High insulation. Engineered for cold environments where heat retention is essential.',
            ],
            [
                'level' => 6,
                'label' => '6/6',
                'description' => 'Maximum insulation. Built for extreme cold - delivers the highest level of warmth and heat retention.',
            ],
        ];


        foreach ($insulations as $insulation) {
            Insulation::query()->updateOrCreate(
                ['level' => $insulation['level']],
                $insulation,
            );
        }

        $breathabilities = [
            [
                'level' => 0,
                'label' => '0/6',
                'description' => 'Non-breathable. No moisture transfer - suited for wind or waterproof protection layers.',
            ],
            [
                'level' => 1,
                'label' => '1/6',
                'description' => 'Very low breathability. Minimal airflow - best for static or low-intensity use.',
            ],
            [
                'level' => 2,
                'label' => '2/6',
                'description' => 'Low breathability. Suitable for light activity where moisture management is not critical.',
            ],
            [
                'level' => 3,
                'label' => '3/6',
                'description' => 'Moderate breathability. Handles light to moderate perspiration during everyday activity.',
            ],
            [
                'level' => 4,
                'label' => '4/6',
                'description' => 'Good breathability. Manages moisture effectively during sustained physical activity.',
            ],
            [
                'level' => 5,
                'label' => '5/6',
                'description' => 'High breathability. Excellent airflow and moisture transfer for intense, high-output activity.',
            ],
            [
                'level' => 6,
                'label' => '6/6',
                'description' => 'Maximum breathability. Engineered for peak performance - superior ventilation and moisture management at all effort levels.',
            ],
        ];


        foreach ($breathabilities as $breathability) {
            Breathability::query()->updateOrCreate(
                ['level' => $breathability['level']],
                $breathability,
            );
        }

        $materials = [
            [
                'material' => 'Nylon Ripstop',
                'image' => 'materials/nylon-ripstop.svg',
                'description' => 'Kain nylon ringan dengan struktur anti sobek untuk shell jacket.',
            ],
            [
                'material' => 'Coated Polyester',
                'image' => 'materials/coated-polyester.svg',
                'description' => 'Polyester dengan lapisan pelindung untuk menahan angin ringan.',
            ],
            [
                'material' => 'Polyester Fleece',
                'image' => 'materials/polyester-fleece.svg',
                'description' => 'Fleece lembut yang menjaga suhu tubuh tetap hangat.',
            ],
            [
                'material' => 'Recycled Polyester',
                'image' => 'materials/recycled-polyester.svg',
                'description' => 'Serat polyester daur ulang yang ringan dan cepat kering.',
            ],
            [
                'material' => 'Quick-Dry Mesh',
                'image' => 'materials/quick-dry-mesh.svg',
                'description' => 'Mesh cepat kering untuk base layer dan running tee.',
            ],
            [
                'material' => 'Stretch Nylon',
                'image' => 'materials/stretch-nylon.svg',
                'description' => 'Nylon elastis untuk celana outdoor yang butuh mobilitas tinggi.',
            ],
            [
                'material' => 'Elastane',
                'image' => 'materials/elastane.svg',
                'description' => 'Serat elastis untuk menambah fleksibilitas bahan.',
            ],
            [
                'material' => 'Merino Wool Blend',
                'image' => 'materials/merino-wool-blend.svg',
                'description' => 'Campuran merino yang lembut, breathable, dan nyaman dipakai lama.',
            ],
        ];

        foreach ($materials as $material) {
            Material::query()->updateOrCreate(
                ['material' => $material['material']],
                $material,
            );
        }
    }
}
