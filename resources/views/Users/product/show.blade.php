@extends('Users.Template.index')

@section('title', $product->name)

@php
    $sanitizeFeatureHtml = fn($html) => \App\Support\HtmlSanitizer::clean(
        (string) $html,
        ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'div', 'a'],
    );
    $materials = $materials ?? collect();
    $careGuides = $careGuides ?? collect();
    $temperatureReference = $temperatureReference ?? null;
    $intensityReference = $intensityReference ?? null;
    $insulationReference = $insulationReference ?? null;
    $breathabilityReference = $breathabilityReference ?? null;
    $productImages = collect($product->images ?? [])
        ->filter(fn($image) => filled($image->image))
        ->sortByDesc(fn($image) => (bool) $image->is_primary)
        ->values();
    $placeholderImage = 'https://via.placeholder.com/1200x1500?text=Gloaming+Imagine';
    $imageUrls = $productImages->map(fn($image) => asset('storage/' . $image->image))->filter()->values();

    if ($imageUrls->isEmpty()) {
        $imageUrls = collect([$placeholderImage]);
    }

    $heroImageUrls = $imageUrls->count() === 1 ? $imageUrls->concat($imageUrls)->values() : $imageUrls;
    $primaryImageUrl = $imageUrls->first() ?? $placeholderImage;
    $imageAt = fn(int $index) => $imageUrls->get($index) ?? $primaryImageUrl;

    $sizeOrder = [
        'XXS' => 10,
        'XS' => 20,
        'S' => 30,
        'M' => 40,
        'L' => 50,
        'XL' => 60,
        'XXL' => 70,
        'XXXL' => 80,
    ];
    $sizeSortKey = function ($variant) use ($sizeOrder) {
        $size = trim((string) $variant->size);
        $normalizedSize = strtoupper($size);

        if ($size === '') {
            return sprintf('999999-%06d', (int) $variant->id);
        }

        $rank = $sizeOrder[$normalizedSize] ?? (is_numeric($size) ? 1000 + (float) $size : 5000);

        return sprintf('%010.2f-%s-%06d', $rank, $normalizedSize, (int) $variant->id);
    };
    $variants = ($product->variants ?? collect())->sortBy($sizeSortKey)->values();
    $availableVariants = $variants->filter(fn($variant) => (int) $variant->stock > 0 && filled($variant->size));
    $priceVariant = ($availableVariants->isNotEmpty() ? $availableVariants : $variants)->sortBy('price')->first();
    $formattedPrice = $priceVariant ? 'Rp ' . number_format($priceVariant->price, 0, ',', '.') : 'Unavailable';
    $hasAvailableVariant = $availableVariants->isNotEmpty();

    $categoryLabel = $product->category->name ?? 'Product';
    $collectionLabel = $product->collection->name ?? null;
    $genderLabel = \Illuminate\Support\Str::headline($product->gender ?: 'unisex');
    $intensityLabel = \Illuminate\Support\Str::headline($intensityReference?->label ?? ($product->intensity ?? 'low'));
    $description = trim((string) $product->description) ?: 'Product detail is being updated.';

    $temperatureValue = max(-10, min(30, (int) ($product->temperature ?? 0)));
    $temperatureStart = max(-10, $temperatureValue - 5);
    $temperatureEnd = min(30, $temperatureValue + 5);
    $intensityStart = ($product->intensity ?? 'low') === 'high' ? 26 : 1;
    $intensityEnd = ($product->intensity ?? 'low') === 'high' ? 50 : 25;
    $insulationPercent = max(0, min(100, (int) ($product->insulation ?? 0)));
    $breathabilityPercent = max(0, min(100, (int) ($product->breathability ?? 0)));
    $insulationLevel = (int) ceil(($insulationPercent / 100) * 6);
    $breathabilityLevel = (int) ceil(($breathabilityPercent / 100) * 6);
    $temperatureLabel = trim((string) ($temperatureReference?->label ?? $temperatureValue . '°C'));
    $temperatureDescription = trim((string) ($temperatureReference?->description ?? ''));
    $intensityDescription = trim((string) ($intensityReference?->description ?? ''));
    $insulationDescription = trim((string) ($insulationReference?->description ?? ''));
    $breathabilityDescription = trim((string) ($breathabilityReference?->description ?? ''));

    $sizeGuideRows = collect(data_get($product->sizeGuide, 'data.sizes', []));
    $sizeGuideRows = $sizeGuideRows->isNotEmpty()
        ? $sizeGuideRows
        : $variants->map(
            fn($variant) => [
                'size' => $variant->size ?: '-',
                'measurements' => [
                    [
                        'label' => 'Stock',
                        'type' => 'simple',
                        'value' => (int) $variant->stock,
                        'unit' => 'pcs',
                    ],
                ],
            ],
        );
    $sizeGuideLabels = $sizeGuideRows
        ->flatMap(fn($row) => collect(data_get($row, 'measurements', []))->pluck('label'))
        ->unique()
        ->values();
    $sizeGuideColumns = max(2, $sizeGuideLabels->count() + 1);

    $formatNumber = function ($value) {
        $number = (float) $value;

        return fmod($number, 1.0) === 0.0 ? (string) (int) $number : number_format($number, 1, '.', '');
    };
    $formatMeasurement = function ($measurement, string $unit = 'cm') use ($formatNumber) {
        if (!$measurement) {
            return '-';
        }

        $sourceUnit = data_get($measurement, 'unit', 'cm');
        $displayUnit = $unit === 'in' && $sourceUnit === 'cm' ? 'in' : $sourceUnit;
        $convert = fn($value) => $unit === 'in' && $sourceUnit === 'cm' ? round(((float) $value) / 2.54, 1) : $value;

        if (data_get($measurement, 'type') === 'range') {
            return $formatNumber($convert(data_get($measurement, 'min'))) .
                '-' .
                $formatNumber($convert(data_get($measurement, 'max'))) .
                ' ' .
                $displayUnit;
        }

        return $formatNumber($convert(data_get($measurement, 'value'))) . ' ' . $displayUnit;
    };
    $measurementFor = fn($row, $label) => collect(data_get($row, 'measurements', []))->firstWhere('label', $label);
    $sizeGuideImageUrl = filled($product->sizeGuide?->img)
        ? asset('storage/' . $product->sizeGuide->img)
        : $primaryImageUrl;
    $sizeGuideTitle = trim((string) ($product->sizeGuide->name ?? 'Size guide'));
    $sizeOptions = ($availableVariants->isNotEmpty() ? $availableVariants : $variants)
        ->pluck('size')
        ->filter()
        ->values();
    $sizeSummary = $sizeOptions->isNotEmpty() ? 'Available sizes: ' . $sizeOptions->join(', ') . '.' : '';
    $fitDescription = trim(
        collect([
            $sizeGuideTitle !== '' ? $sizeGuideTitle . '.' : null,
            $genderLabel . ' fit.',
            $sizeSummary !== '' ? $sizeSummary : null,
        ])
            ->filter()
            ->implode(' '),
    );
    $materialNames = $materials->pluck('material')->filter()->values();
    $materialDescriptions = $materials
        ->pluck('description')
        ->filter(fn($value) => filled(trim((string) $value)))
        ->map(fn($value) => trim((string) $value))
        ->values();
    $materialFeatureImage =
        optional($materials->first(fn($material) => filled($material->image)), function ($material) {
            return asset('storage/' . $material->image);
        }) ?? $imageAt(1);
    $materialSummary = $materialDescriptions->isNotEmpty()
        ? $materialDescriptions->implode(' ')
        : ($materialNames->isNotEmpty()
            ? 'Materials: ' . $materialNames->join(', ') . '.'
            : '');
    $careGuideItems = $careGuides
        ->filter(fn($guide) => filled(trim((string) $guide->answer)))
        ->map(function ($guide) {
            $answer = trim((string) $guide->answer);

            return [
                'question' => trim((string) $guide->question),
                'summary' => \Illuminate\Support\Str::of($answer)
                    ->replace(["\r\n", "\n", "\r"], ' ')
                    ->squish()
                    ->toString(),
                'lines' => collect(preg_split("/\r\n|\n|\r/", $answer) ?: [])
                    ->map(fn($line) => trim((string) $line))
                    ->filter()
                    ->values(),
            ];
        })
        ->values();
    $careSummary = $careGuideItems->pluck('summary')->filter()->first() ?? '';
    $specsSummary = collect([
        $temperatureDescription,
        $intensityDescription,
        $insulationDescription,
        $breathabilityDescription,
    ])
        ->filter()
        ->implode(' ');

    $featureItems = $materials
        ->values()
        ->map(function ($material, $index) use ($imageAt, $sanitizeFeatureHtml) {
            $materialName = trim((string) ($material->material ?? ''));
            $materialDescription = trim((string) ($material->description ?? ''));
            $materialDescriptionHtml = $sanitizeFeatureHtml($materialDescription);

            return [
                'key' => 'material-' . ($material->id ?? $index),
                'label' => $materialName !== '' ? $materialName : 'Material ' . ($index + 1),
                'image' => filled($material->image) ? asset('storage/' . $material->image) : $imageAt($index),
                'description_html' => $materialDescriptionHtml !== ''
                    ? $materialDescriptionHtml
                    : $sanitizeFeatureHtml(
                        $materialName !== ''
                            ? '<p>Material: ' . e($materialName) . '.</p>'
                            : '<p>Material information is being updated.</p>',
                    ),
            ];
        })
        ->values();

    if ($featureItems->isEmpty()) {
        $featureItems = collect([
            [
                'key' => 'material-fallback',
                'label' => 'Material',
                'image' => $materialFeatureImage,
                'description_html' => $sanitizeFeatureHtml(
                    $materialSummary !== '' ? '<p>' . e($materialSummary) . '</p>' : '<p>Material information is being updated.</p>',
                ),
            ],
        ]);
    }

    $initialFeature = $featureItems->first();

    $relatedImageUrl = function ($relatedProduct) use ($placeholderImage) {
        $primary =
            collect($relatedProduct->images)->firstWhere('is_primary', true) ??
            collect($relatedProduct->images)->first();

        return $primary && filled($primary->image) ? asset('storage/' . $primary->image) : $placeholderImage;
    };
    $relatedPrice = function ($relatedProduct) {
        $relatedVariants = collect($relatedProduct->variants);
        $availableRelatedVariants = $relatedVariants->filter(
            fn($variant) => (int) $variant->stock > 0 && filled($variant->size),
        );
        $relatedPriceVariant = ($availableRelatedVariants->isNotEmpty() ? $availableRelatedVariants : $relatedVariants)
            ->sortBy('price')
            ->first();

        return $relatedPriceVariant ? 'Rp ' . number_format($relatedPriceVariant->price, 0, ',', '.') : 'Unavailable';
    };
@endphp

@push('css')
    <style>
        .pd-product-show {
            background: #fff;
            color: #000;
        }

        .pd-gallery-container {
            position: relative;
            min-height: calc(100vh - 75px);
            overflow: hidden;
            background: #fafafa;
        }

        .pd-gallery-track {
            display: flex;
            height: calc(100vh - 75px);
            min-height: 620px;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: grab;
            user-select: none;
        }

        .pd-gallery-track.dragging {
            cursor: grabbing;
            transition: none;
        }

        .pd-gallery-slide {
            min-width: 50%;
            width: 50%;
            height: 100%;
            flex-shrink: 0;
        }

        .pd-gallery-slide img,
        .pd-full-image-section img,
        .pd-gallery-item img,
        .pd-card-image,
        .pd-feature-image,
        .pd-sizing-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .pd-gallery-dots {
            position: absolute;
            bottom: 32px;
            left: 50%;
            z-index: 10;
            display: flex;
            gap: 8px;
            transform: translateX(-50%);
        }

        .pd-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.3);
            border: 0;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s;
        }

        .pd-dot.active {
            width: 20px;
            border-radius: 3px;
            background: #000;
        }

        .pd-arrow {
            position: absolute;
            top: 50%;
            z-index: 10;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateY(-50%);
            transition: opacity 0.3s;
            font-size: 18px;
        }

        .pd-gallery-container:hover .pd-arrow {
            opacity: 1;
        }

        .pd-arrow.prev {
            left: 24px;
        }

        .pd-arrow.next {
            right: 24px;
        }

        .pd-product-card {
            position: absolute;
            top: 75%;
            right: 40px;
            z-index: 10;
            width: 380px;
            max-height: 70vh;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e5e5;
            padding: 24px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-50%);
        }

        .pd-badge {
            display: inline-block;
            margin-bottom: 12px;
            padding: 4px 8px;
            border: 1px solid #000;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .pd-product-title {
            margin-bottom: 8px;
            font-size: 20px;
            font-weight: 400;
            line-height: 1.2;
            letter-spacing: 0;
        }

        .pd-color-name {
            margin-bottom: 12px;
            color: #666;
            font-size: 11px;
        }

        .pd-product-description {
            max-height: 60px;
            overflow: hidden;
            margin-bottom: 10px;
            color: #666;
            font-size: 12px;
            line-height: 1.6;
            transition: max-height 0.3s ease;
        }

        .pd-product-description.expanded {
            max-height: 500px;
        }

        .pd-read-more {
            display: inline-block;
            margin-bottom: 16px;
            border: 0;
            background: none;
            color: #000;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 500;
            padding: 0;
        }

        .pd-read-more:hover {
            text-decoration: underline;
        }

        .pd-select-size-btn {
            width: 100%;
            padding: 14px;
            border: none;
            background: #000;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font: inherit;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .pd-price-tag {
            font-size: 14px;
            letter-spacing: 0;
            text-transform: none;
        }

        .pd-info-links {
            display: flex;
            gap: 16px;
            margin-top: 16px;
            font-size: 11px;
        }

        .pd-info-link {
            color: #666;
            text-decoration: none;
        }

        .pd-specifications-section {
            background: #000;
            color: #fff;
            padding: 50px 0;
        }

        .pd-specs-container,
        .pd-features-container,
        .pd-gallery-section-container,
        .pd-similar-products-container {
            max-width: 2000px;
            margin: 0 auto;
            padding: 0 60px;
        }

        .pd-specs-title {
            margin-bottom: 40px;
            font-size: 30px;
            font-weight: 450;
            letter-spacing: 0;
            line-height: 1.1;
        }

        .pd-specs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pd-spec-card {
            display: flex;
            flex-direction: column;
            min-height: 230px;
            padding: 40px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pd-spec-card:last-child {
            border-right: none;
        }

        .pd-spec-name {
            margin-bottom: 40px;
            font-size: 15px;
            font-weight: 500;
        }

        .pd-spec-bar {
            margin-top: auto;
        }

        .pd-bar-track {
            position: relative;
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 24px;
            background: transparent;
        }

        .pd-bar-segment {
            flex: 1;
            height: 8px;
            border-radius: 1px;
            background: #fff;
        }

        .pd-bar-segment.tall {
            height: 18px;
        }

        .pd-bar-segment.inactive {
            background: rgba(255, 255, 255, 0.25);
        }

        .pd-bar-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pd-spec-circle-content {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: auto;
        }

        .pd-spec-icon {
            position: relative;
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .pd-circle-segments {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .pd-circle-segments .pd-bar-segment {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 2px;
            height: 6px;
            flex: unset;
            border-radius: 1px;
            transform: translateX(-50%) rotate(calc(var(--i) * 6deg)) translateY(-22px);
            transform-origin: center bottom;
        }

        .pd-spec-center-icon {
            position: absolute;
            top: 57%;
            left: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translate(-50%, -50%);
        }

        .pd-spec-center-icon svg {
            width: 100%;
            height: 100%;
            fill: none;
            stroke: #fff;
            stroke-width: 1.5;
        }

        .pd-spec-text {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 16px;
        }

        .pd-specs-footer {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 20px;
            width: 25%;
            margin-left: auto;
            padding-top: 32px;
            text-align: right;
        }

        .pd-specs-description {
            max-width: 600px;
            font-size: 18px;
            font-weight: 300;
            line-height: 1.6;
        }

        .pd-outline-btn {
            padding: 16px 40px;
            border: 1px solid currentColor;
            background: transparent;
            color: inherit;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .pd-outline-btn:hover {
            background: currentColor;
        }

        .pd-outline-btn:hover span {
            color: #fff;
        }

        .pd-specifications-section .pd-outline-btn:hover span {
            color: #000;
        }

        .pd-full-image-section {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f5f5f5;
        }

        .pd-image-overlay-text {
            position: absolute;
            bottom: 60px;
            left: 60px;
            max-width: 640px;
            color: #fff;
        }

        .pd-overlay-badge {
            display: inline-block;
            margin-bottom: 16px;
            padding: 6px 12px;
            border: 1px solid #fff;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .pd-overlay-title {
            font-size: 42px;
            font-weight: 300;
            line-height: 1.3;
            letter-spacing: 0;
        }

        .pd-features-sizing-section,
        .pd-gallery-section,
        .pd-similar-products-section {
            background: #fff;
            padding: 80px 0;
        }

        .pd-similar-products-section {
            border-top: 1px solid #e5e5e5;
        }

        .pd-section-tabs {
            display: flex;
            gap: 40px;
            margin-bottom: 60px;
            border-bottom: 1px solid #e5e5e5;
        }

        .pd-tab {
            padding-bottom: 16px;
            border: 0;
            border-bottom: 2px solid transparent;
            background: none;
            color: #999;
            cursor: pointer;
            font: inherit;
            font-size: 18px;
            transition: all 0.3s;
        }

        .pd-tab.active {
            border-bottom-color: #000;
            color: #000;
        }

        .pd-tab-content {
            display: none;
        }

        .pd-tab-content.active {
            display: block;
        }

        .pd-features-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 0;
            margin-bottom: 80px;
        }

        .pd-features-left {
            display: flex;
            flex-direction: column;
            padding-right: 60px;
        }

        .pd-features-list {
            margin: 0 0 40px;
            padding: 0;
            list-style: none;
        }

        .pd-feature-item {
            margin-bottom: 20px;
            border: 0;
            background: none;
            color: #e5e5e5;
            cursor: pointer;
            display: block;
            font: inherit;
            font-size: 32px;
            font-weight: 300;
            letter-spacing: 0;
            padding: 0;
            text-align: left;
        }

        .pd-feature-item.active {
            color: #000;
        }

        .pd-feature-description {
            max-width: 400px;
            color: #333;
            font-size: 15px;
            line-height: 1.7;
            transition: opacity 0.3s ease;
        }

        .pd-feature-description>*:first-child {
            margin-top: 0;
        }

        .pd-feature-description>*:last-child {
            margin-bottom: 0;
        }

        .pd-feature-description ul,
        .pd-feature-description ol {
            padding-left: 20px;
        }

        .pd-feature-description a {
            color: inherit;
            text-decoration: underline;
        }

        .pd-feature-detail {
            display: flex;
            justify-content: flex-end;
        }

        .pd-feature-image-container {
            width: 55%;
            overflow: hidden;
        }

        .pd-feature-image {
            height: auto;
            transition: opacity 0.3s ease;
        }

        .pd-sizing-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            align-items: flex-start;
        }

        .pd-sizing-left {
            min-height: 600px;
            padding-right: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .pd-sizing-info h3 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 300;
            line-height: 1.3;
            letter-spacing: 0;
        }

        .pd-sizing-image-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .pd-sizing-image {
            max-width: 600px;
            height: auto;
        }

        .pd-gallery-section-header,
        .pd-similar-products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .pd-gallery-section-title,
        .pd-similar-products-title {
            font-size: 28px;
            font-weight: 400;
            letter-spacing: 0;
        }

        .pd-gallery-navigation {
            display: flex;
            gap: 12px;
        }

        .pd-gallery-nav-btn {
            width: 48px;
            height: 48px;
            border: 1px solid #e5e5e5;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font: inherit;
            font-size: 20px;
        }

        .pd-gallery-nav-btn:hover {
            border-color: #000;
            background: #000;
            color: #fff;
        }

        .pd-gallery-scroll-wrapper,
        .pd-similar-products-scroll-wrapper {
            overflow-x: auto;
            cursor: grab;
            user-select: none;
            scrollbar-width: none;
        }

        .pd-gallery-scroll-wrapper::-webkit-scrollbar,
        .pd-similar-products-scroll-wrapper::-webkit-scrollbar {
            display: none;
        }

        .pd-gallery-scroll-wrapper.dragging,
        .pd-similar-products-scroll-wrapper.dragging {
            cursor: grabbing;
        }

        .pd-gallery-scroll-container,
        .pd-similar-products-scroll {
            display: flex;
            gap: 20px;
        }

        .pd-gallery-item {
            flex: 0 0 calc(50% - 10px);
            height: 600px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .pd-gallery-item img {
            transition: transform 0.3s;
        }

        .pd-gallery-item:hover img {
            transform: scale(1.05);
        }

        .pd-product-card-item {
            position: relative;
            flex: 0 0 calc(25% - 15px);
            color: inherit;
            background: #fff;
        }

        .pd-card-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .pd-card-image-wrapper {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .pd-card-image {
            transition: transform 0.3s;
        }

        .pd-product-card-item:hover .pd-card-image {
            transform: scale(1.05);
        }

        .pd-card-info {
            padding: 20px 0;
        }

        .pd-card-name {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.4;
        }

        .pd-card-price {
            font-size: 16px;
            font-weight: 500;
        }

        .pd-product-show .wishlist-btn {
            opacity: 1;
        }

        .pd-card-wishlist,
        .pd-floating-wishlist {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e5e5e5;
            border-radius: 0;
            box-shadow: none;
        }

        .pd-floating-wishlist {
            position: static;
            width: 48px;
            height: 48px;
        }

        .pd-floating-cart {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 40px;
            border-top: 1px solid #e5e5e5;
            background: #fff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .pd-floating-cart.visible {
            transform: translateY(0);
        }

        .pd-floating-cart-info,
        .pd-floating-cart-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pd-floating-cart-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .pd-floating-cart-details h4 {
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .pd-floating-cart-details p {
            font-size: 16px;
            font-weight: 400;
        }

        .pd-add-to-cart {
            padding: 14px 40px;
            border: none;
            background: #000;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pd-add-to-cart:disabled {
            background: #d0d0d0;
            cursor: not-allowed;
        }

        .pd-sidebar-overlay,
        .pd-size-guide-overlay {
            position: fixed;
            inset: 0;
            z-index: 1500;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .pd-sidebar-overlay.active,
        .pd-size-guide-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .pd-sidebar-drawer,
        .pd-specs-drawer,
        .pd-size-guide-drawer {
            position: fixed;
            top: 0;
            right: -600px;
            z-index: 2000;
            width: min(600px, 100%);
            height: 100vh;
            overflow-y: auto;
            background: #fff;
            color: #000;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pd-sidebar-drawer {
            width: min(450px, 100%);
            right: -450px;
        }

        .pd-sidebar-drawer.active,
        .pd-specs-drawer.active,
        .pd-size-guide-drawer.active {
            right: 0;
        }

        .pd-drawer-header {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e5e5;
            background: #fff;
        }

        .pd-drawer-title {
            font-size: 14px;
            font-weight: 600;
        }

        .pd-close-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font: inherit;
            font-size: 24px;
        }

        .pd-drawer-content,
        .pd-specs-drawer-content,
        .pd-size-guide-content {
            padding: 24px;
        }

        .pd-breadcrumb {
            margin-bottom: 12px;
            color: #999;
            font-size: 10px;
        }

        .pd-drawer-product-title {
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: 400;
            line-height: 1.25;
        }

        .pd-drawer-price {
            margin-bottom: 16px;
            font-size: 20px;
        }

        .pd-stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            color: #059669;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .pd-stock-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #059669;
        }

        .pd-section {
            margin-bottom: 24px;
        }

        .pd-size-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .pd-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .pd-size-guide-link {
            border: 0;
            background: none;
            color: #000;
            cursor: pointer;
            font: inherit;
            font-size: 10px;
            font-weight: 500;
            padding: 0;
            text-decoration: underline;
        }

        .pd-size-guide-container {
            max-height: 0;
            overflow: hidden;
            margin-bottom: 16px;
            transition: max-height 0.4s ease;
        }

        .pd-size-guide-container.show {
            max-height: 620px;
        }

        .pd-guide-toggle {
            display: flex;
            width: fit-content;
            margin: 12px 0 16px;
            border: 1px solid #000;
        }

        .pd-guide-toggle-btn {
            padding: 8px 24px;
            border: none;
            background: #fff;
            color: #000;
            cursor: pointer;
            font: inherit;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .pd-guide-toggle-btn.active {
            background: #000;
            color: #fff;
        }

        .pd-size-table {
            width: 100%;
            margin-bottom: 24px;
        }

        .pd-size-table-header,
        .pd-size-table-row {
            display: grid;
            gap: 8px;
        }

        .pd-size-table-header {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #000;
        }

        .pd-size-table-header div {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .pd-size-table-row {
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
            font-size: 13px;
        }

        .pd-size-table-row div:first-child {
            font-weight: 600;
        }

        .pd-size-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .pd-size-btn {
            position: relative;
            display: block;
            padding: 14px 8px;
            border: 1px solid #d0d0d0;
            background: #fff;
            cursor: pointer;
            font-size: 12px;
            text-align: center;
            transition: all 0.2s;
        }

        .pd-size-btn:hover:not(.disabled) {
            border-color: #000;
        }

        .pd-size-btn.selected {
            border-color: #000;
            background: #000;
            color: #fff;
        }

        .pd-size-btn.disabled {
            cursor: not-allowed;
            opacity: 0.3;
        }

        .pd-size-btn input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .pd-size-feedback {
            min-height: 18px;
            margin-top: 10px;
            color: #b02a37;
            font-size: 12px;
        }

        .pd-drawer-cart-btn {
            width: 100%;
            margin-bottom: 16px;
            padding: 16px;
        }

        .pd-shipping-info-box {
            margin-bottom: 20px;
            padding: 14px;
            background: #f8f8f8;
            color: #666;
            font-size: 11px;
            line-height: 1.6;
        }

        .pd-description-section {
            padding: 20px 0;
            border-top: 1px solid #e5e5e5;
        }

        .pd-description-title {
            margin-bottom: 10px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .pd-description-text,
        .pd-accordion-body,
        .pd-spec-detail-text,
        .pd-specs-product-desc,
        .pd-measurement-note {
            color: #666;
            font-size: 12px;
            line-height: 1.7;
        }

        .pd-accordion {
            border-top: 1px solid #e5e5e5;
        }

        .pd-accordion-item {
            border-bottom: 1px solid #e5e5e5;
        }

        .pd-accordion-header {
            width: 100%;
            padding: 16px 0;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            font: inherit;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-align: left;
        }

        .pd-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .pd-accordion-item.open .pd-accordion-content {
            max-height: 600px;
            padding-bottom: 16px;
        }

        .pd-accordion-body ul {
            margin: 0;
            padding-left: 18px;
        }

        .pd-specs-product-info {
            margin-bottom: 40px;
            text-align: center;
        }

        .pd-specs-product-image {
            width: 120px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }

        .pd-specs-product-title {
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 400;
        }

        .pd-spec-detail-section {
            margin-bottom: 40px;
        }

        .pd-spec-detail-title {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .pd-spec-detail-bar .pd-bar-track {
            margin-bottom: 12px;
        }

        .pd-spec-detail-bar .pd-bar-segment {
            background: #d0d0d0;
        }

        .pd-spec-detail-bar .pd-bar-segment.active,
        .pd-specs-drawer .pd-circle-segments .pd-bar-segment.active {
            background: #000;
        }

        .pd-spec-detail-bar .pd-bar-segment.inactive,
        .pd-specs-drawer .pd-circle-segments .pd-bar-segment.inactive {
            background: rgba(0, 0, 0, 0.15);
        }

        .pd-spec-detail-bar .pd-bar-labels {
            color: #999;
        }

        .pd-spec-circle-container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .pd-spec-circle-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pd-size-guide-image {
            width: 100%;
            height: auto;
            margin-top: 32px;
        }

        .pd-measurement-note {
            margin-top: 16px;
        }

        body.dark-mode .pd-product-show,
        body.dark-mode .pd-features-sizing-section,
        body.dark-mode .pd-gallery-section,
        body.dark-mode .pd-similar-products-section,
        body.dark-mode .pd-floating-cart,
        body.dark-mode .pd-sidebar-drawer,
        body.dark-mode .pd-specs-drawer,
        body.dark-mode .pd-size-guide-drawer,
        body.dark-mode .pd-drawer-header {
            background: #111;
            color: #f0f0f0;
        }

        body.dark-mode .pd-product-card,
        body.dark-mode .pd-gallery-nav-btn,
        body.dark-mode .pd-product-card-item,
        body.dark-mode .pd-guide-toggle-btn,
        body.dark-mode .pd-size-btn {
            background: #171717;
            color: #f0f0f0;
        }

        body.dark-mode .pd-color-name,
        body.dark-mode .pd-product-description,
        body.dark-mode .pd-info-link,
        body.dark-mode .pd-feature-description,
        body.dark-mode .pd-description-text,
        body.dark-mode .pd-accordion-body,
        body.dark-mode .pd-spec-detail-text,
        body.dark-mode .pd-specs-product-desc,
        body.dark-mode .pd-measurement-note {
            color: #bbb;
        }

        body.dark-mode .pd-tab.active,
        body.dark-mode .pd-feature-item.active,
        body.dark-mode .pd-read-more,
        body.dark-mode .pd-size-guide-link {
            color: #fff;
        }

        body.dark-mode .pd-tab.active {
            border-bottom-color: #fff;
        }

        body.dark-mode .pd-shipping-info-box,
        body.dark-mode .pd-card-image-wrapper,
        body.dark-mode .pd-gallery-item {
            background: #222;
        }

        body.dark-mode .pd-guide-toggle-btn.active,
        body.dark-mode .pd-size-btn.selected {
            background: #fff;
            color: #111;
        }

        @media (max-width: 1024px) {
            .pd-gallery-slide {
                min-width: 100%;
                width: 100%;
            }

            .pd-product-card {
                right: 5%;
                left: 5%;
                width: 90%;
                top: auto;
                bottom: 20px;
                transform: none;
            }

            .pd-specs-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pd-specs-container,
            .pd-features-container,
            .pd-gallery-section-container,
            .pd-similar-products-container {
                padding: 0 32px;
            }

            .pd-specs-footer {
                width: 50%;
            }

            .pd-features-grid,
            .pd-sizing-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .pd-features-left,
            .pd-sizing-left {
                padding-right: 0;
            }

            .pd-sizing-left {
                min-height: auto;
            }

            .pd-feature-image-container {
                width: 100%;
            }

            .pd-gallery-item {
                flex-basis: calc(100% - 10px);
            }

            .pd-product-card-item {
                flex-basis: calc(50% - 10px);
            }

            .pd-full-image-section {
                height: 70vh;
            }

            .pd-overlay-title {
                font-size: 30px;
            }
        }

        @media (max-width: 768px) {

            .pd-gallery-container,
            .pd-gallery-track {
                min-height: 60vh;
                height: 60vh;
            }

            .pd-arrow {
                display: none;
            }

            .pd-product-card {
                position: static;
                width: 100%;
                max-height: none;
                border-right: none;
                border-left: none;
                box-shadow: none;
            }

            .pd-specs-title,
            .pd-gallery-section-title,
            .pd-similar-products-title {
                font-size: 22px;
            }

            .pd-specs-grid {
                grid-template-columns: 1fr;
            }

            .pd-spec-card {
                border-right: none;
                padding: 28px 20px;
            }

            .pd-specs-container,
            .pd-features-container,
            .pd-gallery-section-container,
            .pd-similar-products-container {
                padding: 0 20px;
            }

            .pd-specs-footer {
                width: 100%;
                align-items: flex-start;
                text-align: left;
            }

            .pd-specs-description {
                font-size: 14px;
            }

            .pd-full-image-section {
                height: 60vh;
            }

            .pd-image-overlay-text {
                bottom: 30px;
                left: 24px;
            }

            .pd-overlay-title {
                font-size: 24px;
            }

            .pd-features-sizing-section,
            .pd-gallery-section,
            .pd-similar-products-section {
                padding: 50px 0;
            }

            .pd-section-tabs {
                gap: 24px;
                margin-bottom: 36px;
            }

            .pd-tab {
                font-size: 15px;
            }

            .pd-feature-item {
                font-size: 22px;
            }

            .pd-gallery-item {
                height: 320px;
            }

            .pd-product-card-item {
                flex-basis: calc(100% - 10px);
            }

            .pd-floating-cart {
                align-items: flex-start;
                flex-direction: column;
                padding: 16px 20px;
            }

            .pd-floating-cart-actions {
                width: 100%;
            }

            .pd-add-to-cart {
                flex: 1;
            }
        }

        @media (max-width: 480px) {

            .pd-gallery-section-header,
            .pd-similar-products-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .pd-drawer-content,
            .pd-specs-drawer-content,
            .pd-size-guide-content {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="pd-product-show" data-product-show>
        <div class="pd-gallery-container">
            <div class="pd-gallery-track" id="pdHeroTrack">
                @foreach ($heroImageUrls as $imageUrl)
                    <div class="pd-gallery-slide">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>

            @if ($heroImageUrls->count() > 1)
                <button type="button" class="pd-arrow prev" data-hero-prev aria-label="Previous image">&lsaquo;</button>
                <button type="button" class="pd-arrow next" data-hero-next aria-label="Next image">&rsaquo;</button>
                <div class="pd-gallery-dots" id="pdHeroDots"></div>
            @endif

            <div class="pd-product-card">
                @if (in_array($product->id, $newArrivalIds))
                    <div class="pd-badge">
                        NEW ARRIVAL
                    </div>
                @endif

                <h1 class="pd-product-title">{{ $product->name }}</h1>
                <div class="pd-color-name">
                    {{ $categoryLabel }}@if ($collectionLabel)
                        / {{ $collectionLabel }}
                    @endif
                </div>
                <p class="pd-product-description" id="pdProductDescription">{{ $description }}</p>
                <button type="button" class="pd-read-more" data-description-toggle>+ Read more</button>
                <button type="button" class="pd-select-size-btn" data-open-product-drawer @disabled(!$hasAvailableVariant)>
                    <span>{{ $hasAvailableVariant ? 'Select size' : 'Unavailable' }}</span>
                    <span class="pd-price-tag">{{ $formattedPrice }}</span>
                </button>
                <div class="pd-info-links">
                    <a href="{{ route('crash-replacement') }}" class="pd-info-link">Crash Replacement</a>
                    <a href="{{ route('faq') }}" class="pd-info-link">Shipping & Delivery</a>
                </div>
            </div>
        </div>

        <section class="pd-specifications-section">
            <div class="pd-specs-container">
                <h2 class="pd-specs-title">Product<br>Specifications</h2>
                <div class="pd-specs-grid">
                    <div class="pd-spec-card">
                        <h3 class="pd-spec-name">Temperature</h3>
                        <div class="pd-spec-bar">
                            <div class="pd-bar-track" data-bar data-active-start="{{ $temperatureStart }}"
                                data-active-end="{{ $temperatureEnd }}" data-min="-10" data-max="30"></div>
                            <div class="pd-bar-labels">
                                <span>-10&deg;C</span><span>0&deg;C</span><span>15&deg;C</span><span>+30&deg;C</span>
                            </div>
                        </div>
                    </div>
                    <div class="pd-spec-card">
                        <h3 class="pd-spec-name">Intensity</h3>
                        <div class="pd-spec-bar">
                            <div class="pd-bar-track" data-bar data-active-start="{{ $intensityStart }}"
                                data-active-end="{{ $intensityEnd }}" data-min="1" data-max="50"></div>
                            <div class="pd-bar-labels"><span>Low</span><span>High</span></div>
                        </div>
                    </div>
                    <div class="pd-spec-card">
                        <h3 class="pd-spec-name">Insulation</h3>
                        <div class="pd-spec-circle-content">
                            <div class="pd-spec-icon">
                                <div class="pd-circle-segments" data-circle data-value="{{ $insulationLevel }}"
                                    data-max="6"></div>
                                <div class="pd-spec-center-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z" />
                                        <circle cx="11.5" cy="18.5" r="1.5" fill="currentColor" />
                                    </svg>
                                </div>
                            </div>
                            <div class="pd-spec-text">
                                <span>Insulation</span>
                                <strong>{{ $insulationReference?->label ?? $insulationLevel . '/6' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="pd-spec-card">
                        <h3 class="pd-spec-name">Breathability</h3>
                        <div class="pd-spec-circle-content">
                            <div class="pd-spec-icon">
                                <div class="pd-circle-segments" data-circle data-value="{{ $breathabilityLevel }}"
                                    data-max="6"></div>
                                <div class="pd-spec-center-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor"
                                            stroke-width="8" opacity="0.3" />
                                        <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor"
                                            stroke-width="1" />
                                    </svg>
                                </div>
                            </div>
                            <div class="pd-spec-text">
                                <span>Breathability</span>
                                <strong>{{ $breathabilityReference?->label ?? $breathabilityLevel . '/6' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pd-specs-footer">
                    <p class="pd-specs-description">
                        {{ \Illuminate\Support\Str::limit($specsSummary !== '' ? $specsSummary : $description, 150) }}
                    </p>
                    <button type="button" class="pd-outline-btn" data-open-specs-drawer><span>See Full
                            Specifications</span></button>
                </div>
            </div>
        </section>

        <section class="pd-full-image-section">
            <img src="{{ $imageAt(1) }}" alt="{{ $product->name }}">
            <div class="pd-image-overlay-text">
                <div class="pd-overlay-badge">{{ $temperatureLabel }}</div>
                <h2 class="pd-overlay-title">
                    {{ $intensityDescription !== '' ? $intensityDescription : $genderLabel . ' product built for ' . strtolower($intensityLabel) . ' intensity use.' }}
                </h2>
            </div>
        </section>

        <section class="pd-features-sizing-section" id="featuresSection">
            <div class="pd-features-container">
                <div class="pd-section-tabs">
                    <button type="button" class="pd-tab active" data-product-tab="features">Features</button>
                    <button type="button" class="pd-tab" data-product-tab="sizing">Sizing</button>
                </div>

                <div class="pd-tab-content active" data-product-tab-panel="features">
                    <div class="pd-features-grid">
                        <div class="pd-features-left">
                            <div class="pd-features-list">
                                @foreach ($featureItems as $feature)
                                    <button type="button" class="pd-feature-item {{ $loop->first ? 'active' : '' }}"
                                        data-feature="{{ $feature['key'] }}">{{ $feature['label'] }}</button>
                                @endforeach
                            </div>
                            <div class="pd-feature-description" id="pdFeatureDescription">
                                {!! $initialFeature['description_html'] !!}</div>
                        </div>
                        <div class="pd-feature-detail">
                            <div class="pd-feature-image-container">
                                <img src="{{ $initialFeature['image'] }}" alt="{{ $initialFeature['label'] }}"
                                    class="pd-feature-image" id="pdMainFeatureImage">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pd-tab-content" data-product-tab-panel="sizing">
                    <div class="pd-sizing-content">
                        <div class="pd-sizing-left">
                            <div class="pd-sizing-info">
                                <h3>{{ $fitDescription !== '' ? $fitDescription : 'Use the size guide to choose the best fit.' }}
                                </h3>
                                <button type="button" class="pd-outline-btn" data-open-size-guide><span>Size
                                        Guide</span></button>
                            </div>
                        </div>
                        <div class="pd-sizing-image-wrapper">
                            <img src="{{ $sizeGuideImageUrl }}" alt="{{ $sizeGuideTitle }}" class="pd-sizing-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pd-gallery-section">
            <div class="pd-gallery-section-container">
                <div class="pd-gallery-section-header">
                    <h2 class="pd-gallery-section-title">Gallery</h2>
                    <div class="pd-gallery-navigation">
                        <button type="button" class="pd-gallery-nav-btn" data-scroll-prev="gallery"
                            aria-label="Previous gallery image">&larr;</button>
                        <button type="button" class="pd-gallery-nav-btn" data-scroll-next="gallery"
                            aria-label="Next gallery image">&rarr;</button>
                    </div>
                </div>
                <div class="pd-gallery-scroll-wrapper" data-scroll-wrapper="gallery">
                    <div class="pd-gallery-scroll-container">
                        @foreach ($imageUrls as $imageUrl)
                            <div class="pd-gallery-item">
                                <img src="{{ $imageUrl }}"
                                    alt="{{ $product->name }} gallery {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        @if ($relatedProducts->isNotEmpty())
            <div class="collection-header">
                <h2 class="collection-title">Related Product</h2>
                <div class="collection-nav">
                    <button type="button" class="collection-prev">←</button>
                    <button type="button" class="collection-next">→</button>
                </div>
            </div>

            <div class="block-wrapper-outer">
                <section class="block-wrapper" data-carousel="best-seller">
                    @foreach ($relatedProducts as $prod)
                        @php
                            $primaryImage = collect($prod->images)->firstWhere('is_primary', 1);
                            $hoverImage = collect($prod->images)->firstWhere('is_hover', 1);
                        @endphp

                        <a href="{{ route('product.show', $prod->slug) }}" class="product-block">

                            @if (in_array($prod->id, $newArrivalIds))
                                <div class="product-badges">
                                    <span class="new-arrival-badge">NEW ARRIVAL</span>
                                </div>
                            @endif
                            <div class="product-image-wrapper">
                                <img src="{{ $primaryImage ? asset('storage/' . $primaryImage['image']) : 'https://via.placeholder.com/600x750' }}"
                                    alt="{{ $prod->name }}" class="product-image img-main">

                                <img src="{{ $hoverImage ? asset('storage/' . $hoverImage['image']) : ($primaryImage ? asset('storage/' . $primaryImage['image']) : '') }}"
                                    alt="{{ $prod->name }}" class="product-image img-hover">
                            </div>

                            <div class="product-info">
                                <div class="product-name">{{ $prod->name }}</div>
                                <div class="product-price">{{ $relatedPrice($prod) }}</div>
                            </div>

                        </a>
                    @endforeach
                </section>
            </div>
        @endif

        <div class="pd-floating-cart" id="pdFloatingCart">
            <div class="pd-floating-cart-info">
                <img src="{{ $primaryImageUrl }}" alt="{{ $product->name }}" class="pd-floating-cart-image">
                <div class="pd-floating-cart-details">
                    <h4>{{ $product->name }}</h4>
                    <p>{{ $formattedPrice }}</p>
                </div>
            </div>
            <div class="pd-floating-cart-actions">
                <button type="button" class="wishlist-btn pd-floating-wishlist" data-product-id="{{ $product->id }}"
                    aria-label="Add to wishlist" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button type="button" class="pd-add-to-cart add-cart-btn" data-product-cart-button
                    data-product-cart-trigger @disabled(!$hasAvailableVariant)>
                    {{ $hasAvailableVariant ? 'Add to Cart' : 'Unavailable' }}
                </button>
            </div>
        </div>

        <div class="pd-sidebar-overlay" id="pdProductOverlay" data-close-product-drawer></div>
        <aside class="pd-sidebar-drawer" id="pdProductDrawer" aria-hidden="true">
            <div class="pd-drawer-header">
                <div class="pd-drawer-title">Select Options</div>
                <button type="button" class="pd-close-btn" data-close-product-drawer aria-label="Close">&times;</button>
            </div>
            <div class="pd-drawer-content">
                <div class="pd-breadcrumb">Home / {{ $categoryLabel }}@if ($collectionLabel)
                        / {{ $collectionLabel }}
                    @endif
                </div>
                <h2 class="pd-drawer-product-title">{{ $product->name }}</h2>
                <div class="pd-drawer-price">{{ $formattedPrice }}</div>
                <div class="pd-stock-badge"><span
                        class="pd-stock-dot"></span>{{ $hasAvailableVariant ? 'In stock' : 'Out of stock' }}</div>

                <div class="pd-section">
                    <div class="pd-size-header">
                        <div class="pd-section-label">Size</div>
                        <button type="button" class="pd-size-guide-link" data-toggle-inline-size-guide>Size
                            Guide</button>
                    </div>

                    <div class="pd-size-guide-container" id="pdInlineSizeGuide">
                        <div class="pd-guide-toggle">
                            <button type="button" class="pd-guide-toggle-btn active" data-guide-unit="cm"
                                data-guide-scope="inline">CM</button>
                            <button type="button" class="pd-guide-toggle-btn" data-guide-unit="in"
                                data-guide-scope="inline">IN</button>
                        </div>
                        <div data-guide-table="inline-cm">
                            <div class="pd-size-table">
                                <div class="pd-size-table-header"
                                    style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                    <div>Size</div>
                                    @foreach ($sizeGuideLabels as $label)
                                        <div>{{ $label }}</div>
                                    @endforeach
                                </div>
                                @foreach ($sizeGuideRows as $row)
                                    <div class="pd-size-table-row"
                                        style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                        <div>{{ data_get($row, 'size', '-') }}</div>
                                        @foreach ($sizeGuideLabels as $label)
                                            <div>{{ $formatMeasurement($measurementFor($row, $label), 'cm') }}</div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div data-guide-table="inline-in" style="display:none;">
                            <div class="pd-size-table">
                                <div class="pd-size-table-header"
                                    style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                    <div>Size</div>
                                    @foreach ($sizeGuideLabels as $label)
                                        <div>{{ $label }}</div>
                                    @endforeach
                                </div>
                                @foreach ($sizeGuideRows as $row)
                                    <div class="pd-size-table-row"
                                        style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                        <div>{{ data_get($row, 'size', '-') }}</div>
                                        @foreach ($sizeGuideLabels as $label)
                                            <div>{{ $formatMeasurement($measurementFor($row, $label), 'in') }}</div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pd-size-grid">
                        @forelse ($variants as $variant)
                            @php
                                $variantUnavailable = (int) $variant->stock < 1 || blank($variant->size);
                            @endphp
                            <label class="pd-size-btn {{ $variantUnavailable ? 'disabled' : '' }}">
                                <input type="radio" name="product-size" value="{{ $variant->size }}"
                                    data-variant-id="{{ $variant->id }}"
                                    data-price="Rp {{ number_format($variant->price, 0, ',', '.') }}"  {{-- tambah ini --}}
                                    data-checkout-url="{{ route('checkout.show', $variant->id) }}"
                                    @disabled($variantUnavailable)>
                                <span>{{ $variant->size ?: '-' }}</span>
                            </label>
                        @empty
                            <span class="pd-size-btn disabled">No size</span>
                        @endforelse
                    </div>
                    <div class="pd-size-feedback" id="pdSizeFeedback"></div>
                </div>

                <button type="button" class="pd-add-to-cart pd-drawer-cart-btn add-cart-btn" data-product-cart-button
                    data-product-cart-trigger
                    @disabled(!$hasAvailableVariant)>{{ $hasAvailableVariant ? 'Select a Size' : 'Unavailable' }}</button>
                <div class="pd-shipping-info-box">Free shipping rules and final delivery details are confirmed during
                    checkout.</div>

                <div class="pd-description-section">
                    <div class="pd-description-title">Description</div>
                    <div class="pd-description-text">{{ $description }}</div>
                </div>
                <div class="pd-accordion">
                    <div class="pd-accordion-item">
                        <button type="button" class="pd-accordion-header" data-accordion-toggle>
                            <span>Features</span><span class="pd-accordion-icon">+</span>
                        </button>
                        <div class="pd-accordion-content">
                            <div class="pd-accordion-body">
                                <ul>
                                    <li>{{ $temperatureLabel }}</li>
                                    <li>{{ $intensityLabel }} intensity profile</li>
                                    @if ($temperatureDescription !== '')
                                        <li>{{ $temperatureDescription }}</li>
                                    @endif
                                    @if ($intensityDescription !== '')
                                        <li>{{ $intensityDescription }}</li>
                                    @endif
                                    @if ($sizeSummary !== '')
                                        <li>{{ $sizeSummary }}</li>
                                    @endif
                                    <li>{{ $product->weight ? number_format($product->weight, 0, ',', '.') . ' gr weight' : 'Lightweight daily construction' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pd-accordion-item">
                        <button type="button" class="pd-accordion-header" data-accordion-toggle>
                            <span>Materials & Care</span><span class="pd-accordion-icon">+</span>
                        </button>
                        <div class="pd-accordion-content">
                            <div class="pd-accordion-body">
                                @if ($materials->isNotEmpty())
                                    <ul>
                                        @foreach ($materials as $material)
                                            <li>
                                                {{ $material->material }}
                                                @if (filled($material->description))
                                                    - {{ $material->description }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if ($careGuideItems->isNotEmpty())
                                    <ul>
                                        @foreach ($careGuideItems as $guide)
                                            <li>{{ $guide['question'] }}: {{ $guide['summary'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if ($materials->isEmpty() && $careGuideItems->isEmpty())
                                    Care information is being updated.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="pd-sidebar-overlay" id="pdSpecsOverlay" data-close-specs-drawer></div>
        <aside class="pd-specs-drawer" id="pdSpecsDrawer" aria-hidden="true">
            <div class="pd-drawer-header">
                <div class="pd-drawer-title">{{ $product->name }}</div>
                <button type="button" class="pd-close-btn" data-close-specs-drawer aria-label="Close">&times;</button>
            </div>
            <div class="pd-specs-drawer-content">
                <div class="pd-specs-product-info">
                    <img src="{{ $primaryImageUrl }}" alt="{{ $product->name }}" class="pd-specs-product-image">
                    <h2 class="pd-specs-product-title">{{ $product->name }}</h2>
                    <p class="pd-specs-product-desc">{{ $description }}</p>
                </div>
                <div class="pd-spec-detail-section">
                    <h3 class="pd-spec-detail-title">Temperature</h3>
                    <div class="pd-spec-detail-bar">
                        <div class="pd-bar-track" data-bar data-active-start="{{ $temperatureStart }}"
                            data-active-end="{{ $temperatureEnd }}" data-min="-10" data-max="30"></div>
                        <div class="pd-bar-labels">
                            <span>-10&deg;C</span><span>0&deg;C</span><span>15&deg;C</span><span>+30&deg;C</span>
                        </div>
                    </div>
                    <p class="pd-spec-detail-text">
                        {{ $temperatureDescription !== '' ? $temperatureLabel . ': ' . $temperatureDescription : 'Temperature reference: ' . $temperatureValue . '°C.' }}
                    </p>
                </div>
                <div class="pd-spec-detail-section">
                    <h3 class="pd-spec-detail-title">Intensity</h3>
                    <div class="pd-spec-detail-bar">
                        <div class="pd-bar-track" data-bar data-active-start="{{ $intensityStart }}"
                            data-active-end="{{ $intensityEnd }}" data-min="1" data-max="50"></div>
                        <div class="pd-bar-labels"><span>Low</span><span>High</span></div>
                    </div>
                    <p class="pd-spec-detail-text">
                        {{ $intensityDescription !== '' ? $intensityDescription : 'This product is set for ' . strtolower($intensityLabel) . ' intensity use.' }}
                    </p>
                </div>
                <div class="pd-spec-detail-section">
                    <div class="pd-spec-circle-container">
                        <div class="pd-spec-circle-item">
                            <div class="pd-spec-icon">
                                <div class="pd-circle-segments" data-circle data-value="{{ $breathabilityLevel }}"
                                    data-max="6"></div>
                                <div class="pd-spec-center-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor"
                                            stroke-width="8" opacity="0.3" />
                                        <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor"
                                            stroke-width="1" />
                                    </svg>
                                </div>
                            </div>
                            <div class="pd-spec-text">
                                <span>Breathability</span>
                                <strong>{{ $breathabilityReference?->label ?? $breathabilityLevel . '/6' }}</strong>
                            </div>
                        </div>
                        <div class="pd-spec-circle-item">
                            <div class="pd-spec-icon">
                                <div class="pd-circle-segments" data-circle data-value="{{ $insulationLevel }} "
                                    data-max="6"></div>
                                <div class="pd-spec-center-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" style="stroke:#000 !important;">
                                        <path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z" />
                                        <circle cx="11.5" cy="18.5" r="1.5" fill="currentColor" />
                                    </svg>
                                </div>
                            </div>
                            <div class="pd-spec-text">
                                <span>Insulation
                                    ss</span><strong>{{ $insulationReference?->label ?? $insulationLevel . '/6' }}</strong>
                            </div>
                        </div>
                    </div>
                    <p class="pd-spec-detail-text">
                        {{ collect([$breathabilityDescription, $insulationDescription])->filter()->implode(' ') ?:'Breathability ' . $breathabilityPercent . '% and insulation ' . $insulationPercent . '% are based on the product specification configured in admin.' }}
                    </p>
                </div>
            </div>
        </aside>

        <div class="pd-size-guide-overlay" id="pdSizeGuideOverlay" data-close-size-guide></div>
        <aside class="pd-size-guide-drawer" id="pdSizeGuideDrawer" aria-hidden="true">
            <div class="pd-drawer-header">
                <div class="pd-drawer-title">Size Guide</div>
                <button type="button" class="pd-close-btn" data-close-size-guide aria-label="Close">&times;</button>
            </div>
            <div class="pd-size-guide-content">
                <div class="pd-guide-toggle">
                    <button type="button" class="pd-guide-toggle-btn active" data-guide-unit="cm"
                        data-guide-scope="drawer">CM</button>
                    <button type="button" class="pd-guide-toggle-btn" data-guide-unit="in"
                        data-guide-scope="drawer">IN</button>
                </div>
                <div data-guide-table="drawer-cm">
                    <div class="pd-size-table">
                        <div class="pd-size-table-header"
                            style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                            <div>Size</div>
                            @foreach ($sizeGuideLabels as $label)
                                <div>{{ $label }}</div>
                            @endforeach
                        </div>
                        @foreach ($sizeGuideRows as $row)
                            <div class="pd-size-table-row"
                                style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                <div>{{ data_get($row, 'size', '-') }}</div>
                                @foreach ($sizeGuideLabels as $label)
                                    <div>{{ $formatMeasurement($measurementFor($row, $label), 'cm') }}</div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div data-guide-table="drawer-in" style="display:none;">
                    <div class="pd-size-table">
                        <div class="pd-size-table-header"
                            style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                            <div>Size</div>
                            @foreach ($sizeGuideLabels as $label)
                                <div>{{ $label }}</div>
                            @endforeach
                        </div>
                        @foreach ($sizeGuideRows as $row)
                            <div class="pd-size-table-row"
                                style="grid-template-columns: repeat({{ $sizeGuideColumns }}, minmax(0, 1fr));">
                                <div>{{ data_get($row, 'size', '-') }}</div>
                                @foreach ($sizeGuideLabels as $label)
                                    <div>{{ $formatMeasurement($measurementFor($row, $label), 'in') }}</div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <img src="{{ $sizeGuideImageUrl }}" alt="{{ $sizeGuideTitle }}" class="pd-size-guide-image">
                <p class="pd-measurement-note">
                    {{ $careSummary !== '' ? $careSummary : 'Measure on a flat surface and compare with the table above. Pick the larger size if you are between two sizes.' }}
                </p>
            </div>
        </aside>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-product-show]');
            if (!root) return;

            const featureData = @json(collect($featureItems)->keyBy('key'));
            const hasAvailableVariant = @json($hasAvailableVariant);
            const body = document.body;
            const productDrawer = document.getElementById('pdProductDrawer');
            const productOverlay = document.getElementById('pdProductOverlay');
            const specsDrawer = document.getElementById('pdSpecsDrawer');
            const specsOverlay = document.getElementById('pdSpecsOverlay');
            const sizeGuideDrawer = document.getElementById('pdSizeGuideDrawer');
            const sizeGuideOverlay = document.getElementById('pdSizeGuideOverlay');
            const sizeFeedback = document.getElementById('pdSizeFeedback');
            const cartButtons = root.querySelectorAll('[data-product-cart-button]');
            let selectedVariantId = '';

            const setBodyLock = () => {
                const hasOpenDrawer = [productDrawer, specsDrawer, sizeGuideDrawer].some((drawer) => drawer
                    ?.classList.contains('active'));
                const hasOpenSitePanel = ['shopSidebar', 'cartSidebar'].some((id) => document.getElementById(id)
                    ?.classList.contains('open'));
                body.classList.toggle('body-lock', hasOpenDrawer || hasOpenSitePanel);
            };
            const openDrawer = (drawer, overlay) => {
                drawer?.classList.add('active');
                overlay?.classList.add('active');
                drawer?.setAttribute('aria-hidden', 'false');
                setBodyLock();
            };
            const closeDrawer = (drawer, overlay) => {
                drawer?.classList.remove('active');
                overlay?.classList.remove('active');
                drawer?.setAttribute('aria-hidden', 'true');
                setBodyLock();
            };
            const showSizeRequired = () => {
                const message = hasAvailableVariant ?
                    'Pilih size dulu sebelum menambahkan ke cart.' :
                    'Produk ini sedang tidak tersedia.';
                if (sizeFeedback) {
                    sizeFeedback.textContent = message;
                }
                window.appNotify?.warning(message, hasAvailableVariant ? 'Size belum dipilih' : 'Stok habis');
            };

            root.querySelectorAll('[data-open-product-drawer]').forEach((button) => {
                button.addEventListener('click', () => openDrawer(productDrawer, productOverlay));
            });
            root.querySelectorAll('[data-close-product-drawer]').forEach((button) => {
                button.addEventListener('click', () => closeDrawer(productDrawer, productOverlay));
            });
            root.querySelector('[data-open-specs-drawer]')?.addEventListener('click', () => openDrawer(specsDrawer,
                specsOverlay));
            root.querySelectorAll('[data-close-specs-drawer]').forEach((button) => {
                button.addEventListener('click', () => closeDrawer(specsDrawer, specsOverlay));
            });
            root.querySelectorAll('[data-open-size-guide]').forEach((button) => {
                button.addEventListener('click', () => openDrawer(sizeGuideDrawer, sizeGuideOverlay));
            });
            root.querySelectorAll('[data-close-size-guide]').forEach((button) => {
                button.addEventListener('click', () => closeDrawer(sizeGuideDrawer, sizeGuideOverlay));
            });
            root.querySelector('[data-toggle-inline-size-guide]')?.addEventListener('click', () => {
                document.getElementById('pdInlineSizeGuide')?.classList.toggle('show');
            });

            root.querySelector('[data-description-toggle]')?.addEventListener('click', (event) => {
                const description = document.getElementById('pdProductDescription');
                if (!description) return;
                description.classList.toggle('expanded');
                event.currentTarget.textContent = description.classList.contains('expanded') ?
                    '- Read less' : '+ Read more';
            });

            root.querySelectorAll('input[name="product-size"]').forEach((input) => {
    input.addEventListener('change', () => {
        selectedVariantId = input.dataset.variantId || '';
        root.querySelectorAll('.pd-size-btn').forEach((label) => label.classList.remove('selected'));
        input.closest('.pd-size-btn')?.classList.add('selected');
        cartButtons.forEach((button) => {
            button.dataset.variantId = selectedVariantId;
            button.disabled = false;
            button.textContent = 'Add to Cart';
        });
        if (sizeFeedback) sizeFeedback.textContent = '';

        // --- Tambah bagian ini ---
        const selectedPrice = input.dataset.price;
        if (selectedPrice) {
            // Update harga di drawer
            root.querySelectorAll('.pd-drawer-price').forEach(el => el.textContent = selectedPrice);
            // Update harga di floating cart
            const floatingPrice = root.querySelector('.pd-floating-cart-details p');
            if (floatingPrice) floatingPrice.textContent = selectedPrice;
            // Update harga di tombol select size
            const priceTag = root.querySelector('.pd-price-tag');
            if (priceTag) priceTag.textContent = selectedPrice;
        }
        // --- Akhir tambahan ---
    });
});
            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-product-cart-trigger]');
                if (!trigger || !root.contains(trigger)) return;

                if (!trigger.dataset.variantId) {
                    event.preventDefault();
                    event.stopPropagation();
                    openDrawer(productDrawer, productOverlay);
                    showSizeRequired();
                    return;
                }

                if (productDrawer?.classList.contains('active')) {
                    window.setTimeout(() => closeDrawer(productDrawer, productOverlay), 0);
                }
            }, true);

            document.addEventListener('product:size-required', () => {
                openDrawer(productDrawer, productOverlay);
                showSizeRequired();
            });

            root.querySelectorAll('[data-guide-unit]').forEach((button) => {
                button.addEventListener('click', () => {
                    const scope = button.dataset.guideScope;
                    const unit = button.dataset.guideUnit;
                    root.querySelectorAll(`[data-guide-scope="${scope}"]`).forEach((item) => item
                        .classList.remove('active'));
                    button.classList.add('active');
                    root.querySelectorAll(`[data-guide-table^="${scope}-"]`).forEach((table) => {
                        table.style.display = table.dataset.guideTable ===
                            `${scope}-${unit}` ? 'block' : 'none';
                    });
                });
            });

            root.querySelectorAll('[data-accordion-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const item = button.closest('.pd-accordion-item');
                    const wasOpen = item?.classList.contains('open');
                    root.querySelectorAll('.pd-accordion-item').forEach((accordionItem) => {
                        accordionItem.classList.remove('open');
                        const icon = accordionItem.querySelector('.pd-accordion-icon');
                        if (icon) icon.textContent = '+';
                    });
                    if (!wasOpen) {
                        item?.classList.add('open');
                        const icon = button.querySelector('.pd-accordion-icon');
                        if (icon) icon.textContent = '-';
                    }
                });
            });

            root.querySelectorAll('[data-product-tab]').forEach((tab) => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.productTab;
                    root.querySelectorAll('[data-product-tab]').forEach((item) => item.classList
                        .remove('active'));
                    root.querySelectorAll('[data-product-tab-panel]').forEach((panel) => {
                        panel.classList.toggle('active', panel.dataset.productTabPanel ===
                            target);
                    });
                    tab.classList.add('active');
                });
            });

            root.querySelectorAll('[data-feature]').forEach((button) => {
                button.addEventListener('click', () => {
                    const data = featureData[button.dataset.feature];
                    const image = document.getElementById('pdMainFeatureImage');
                    const description = document.getElementById('pdFeatureDescription');
                    if (!data || !image || !description) return;

                    root.querySelectorAll('[data-feature]').forEach((item) => item.classList.remove(
                        'active'));
                    button.classList.add('active');
                    image.style.opacity = '0';
                    description.style.opacity = '0';
                    window.setTimeout(() => {
                        image.src = data.image;
                        image.alt = data.label;
                        description.innerHTML = data.description_html;
                        image.style.opacity = '1';
                        description.style.opacity = '1';
                    }, 180);
                });
            });

            const buildBarSegments = (barEl) => {
                const min = Number(barEl.dataset.min);
                const max = Number(barEl.dataset.max);
                const activeStart = Number(barEl.dataset.activeStart);
                const activeEnd = Number(barEl.dataset.activeEnd);
                barEl.innerHTML = '';
                for (let i = min; i <= max; i += 1) {
                    const segment = document.createElement('div');
                    const isActive = i >= activeStart && i <= activeEnd;
                    const isTall = i === activeStart || i === activeEnd;
                    segment.className =
                        `pd-bar-segment${isActive ? ' active' : ' inactive'}${isTall ? ' tall' : ''}`;
                    barEl.appendChild(segment);
                }
            };
            const buildCircleSegments = (circleEl) => {
                const value = Number(circleEl.dataset.value);
                const max = Number(circleEl.dataset.max);
                const total = 60;
                const activeBars = Math.round((value / max) * total);
                circleEl.innerHTML = '';
                for (let i = 0; i < total; i += 1) {
                    const segment = document.createElement('div');
                    segment.className = `pd-bar-segment${i < activeBars ? ' active' : ' inactive'}`;
                    segment.style.setProperty('--i', i);
                    circleEl.appendChild(segment);
                }
            };
            root.querySelectorAll('[data-bar]').forEach(buildBarSegments);
            root.querySelectorAll('[data-circle]').forEach(buildCircleSegments);

            const track = document.getElementById('pdHeroTrack');
            const dotsContainer = document.getElementById('pdHeroDots');
            const slides = track ? [...track.querySelectorAll('.pd-gallery-slide')] : [];
            let currentSlide = 0;
            let heroTimer = null;
            let heroStartX = 0;
            let heroDragging = false;

            const visibleSlides = () => window.innerWidth <= 1024 ? 1 : 2;
            const maxSlide = () => Math.max(0, slides.length - visibleSlides());
            const renderDots = () => {
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';
                for (let i = 0; i <= maxSlide(); i += 1) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = `pd-dot${i === currentSlide ? ' active' : ''}`;
                    dot.setAttribute('aria-label', `Go to image ${i + 1}`);
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                }
            };
            const goToSlide = (index) => {
                if (!track || !slides.length) return;
                currentSlide = Math.max(0, Math.min(index, maxSlide()));
                track.style.transform = `translateX(-${currentSlide * (100 / visibleSlides())}%)`;
                dotsContainer?.querySelectorAll('.pd-dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            };
            const nextSlide = () => goToSlide(currentSlide >= maxSlide() ? 0 : currentSlide + 1);
            const prevSlide = () => goToSlide(currentSlide <= 0 ? maxSlide() : currentSlide - 1);
            const startHero = () => {
                if (slides.length <= visibleSlides()) return;
                window.clearInterval(heroTimer);
                heroTimer = window.setInterval(nextSlide, 5000);
            };
            const stopHero = () => window.clearInterval(heroTimer);

            renderDots();
            goToSlide(0);
            startHero();
            root.querySelector('[data-hero-next]')?.addEventListener('click', nextSlide);
            root.querySelector('[data-hero-prev]')?.addEventListener('click', prevSlide);
            track?.addEventListener('mouseenter', stopHero);
            track?.addEventListener('mouseleave', startHero);
            track?.addEventListener('pointerdown', (event) => {
                heroDragging = true;
                heroStartX = event.clientX;
                track.classList.add('dragging');
                track.setPointerCapture?.(event.pointerId);
                stopHero();
            });
            track?.addEventListener('pointerup', (event) => {
                if (!heroDragging) return;
                const moved = event.clientX - heroStartX;
                heroDragging = false;
                track.classList.remove('dragging');
                if (track.hasPointerCapture?.(event.pointerId)) {
                    track.releasePointerCapture(event.pointerId);
                }
                if (moved < -80) nextSlide();
                if (moved > 80) prevSlide();
                startHero();
            });
            const cancelHeroDrag = (event) => {
                if (!heroDragging) return;
                heroDragging = false;
                track.classList.remove('dragging');
                if (event?.pointerId && track.hasPointerCapture?.(event.pointerId)) {
                    track.releasePointerCapture(event.pointerId);
                }
                startHero();
            };
            track?.addEventListener('pointerleave', cancelHeroDrag);
            track?.addEventListener('pointercancel', cancelHeroDrag);
            window.addEventListener('resize', () => {
                renderDots();
                goToSlide(currentSlide);
            });

            const setupScroller = (name) => {
                const wrapper = root.querySelector(`[data-scroll-wrapper="${name}"]`);
                if (!wrapper) return;

                const scrollByItem = (direction) => {
                    const item = wrapper.querySelector(':scope > * > *');
                    const gap = 20;
                    const amount = item ? item.getBoundingClientRect().width + gap : wrapper.clientWidth *
                        0.8;
                    wrapper.scrollBy({
                        left: amount * direction,
                        behavior: 'smooth'
                    });
                };

                root.querySelector(`[data-scroll-next="${name}"]`)?.addEventListener('click', () =>
                    scrollByItem(1));
                root.querySelector(`[data-scroll-prev="${name}"]`)?.addEventListener('click', () =>
                    scrollByItem(-1));

                let isDragging = false;
                let startX = 0;
                let startScroll = 0;
                let movedDistance = 0;
                wrapper.addEventListener('pointerdown', (event) => {
                    if (event.target.closest('button, input, select, textarea')) {
                        return;
                    }

                    isDragging = true;
                    movedDistance = 0;
                    startX = event.clientX;
                    startScroll = wrapper.scrollLeft;
                    wrapper.classList.add('dragging');
                    wrapper.setPointerCapture(event.pointerId);
                });
                wrapper.addEventListener('pointermove', (event) => {
                    if (!isDragging) return;
                    const distance = event.clientX - startX;
                    movedDistance = Math.abs(distance);
                    wrapper.scrollLeft = startScroll - distance;
                });
                const stopDragging = (event) => {
                    if (!isDragging) return;
                    isDragging = false;
                    wrapper.classList.remove('dragging');
                    if (event?.pointerId && wrapper.hasPointerCapture?.(event.pointerId)) {
                        wrapper.releasePointerCapture(event.pointerId);
                    }
                };
                wrapper.addEventListener('pointerup', stopDragging);
                wrapper.addEventListener('pointerleave', stopDragging);
                wrapper.addEventListener('pointercancel', stopDragging);
                wrapper.addEventListener('click', (event) => {
                    if (movedDistance <= 10) return;

                    event.preventDefault();
                    event.stopPropagation();
                    movedDistance = 0;
                }, true);
            };
            setupScroller('gallery');
            setupScroller('products');

            const floatingCart = document.getElementById('pdFloatingCart');
            const updateFloatingCart = () => {
                floatingCart?.classList.toggle('visible', window.scrollY > window.innerHeight * 0.5);
            };
            updateFloatingCart();
            window.addEventListener('scroll', updateFloatingCart, {
                passive: true
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeDrawer(productDrawer, productOverlay);
                    closeDrawer(specsDrawer, specsOverlay);
                    closeDrawer(sizeGuideDrawer, sizeGuideOverlay);
                }
                if (event.key === 'ArrowLeft') prevSlide();
                if (event.key === 'ArrowRight') nextSlide();
            });
        });
    </script>
@endpush
