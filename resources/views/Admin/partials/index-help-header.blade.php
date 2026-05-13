@php
    $heading = $heading ?? '';
    $headingClass = $headingClass ?? 'fw-semibold mb-0';
    $createRoute = $createRoute ?? null;
    $createLabel = $createLabel ?? '';
    $createClass = $createClass ?? 'btn btn-success';
    $collapseId = $collapseId ?? 'resourceInfoCollapse';
    $helpTitle = $helpTitle ?? 'Keterangan Data';
    $messages = collect($messages ?? [])->filter()->values();
    $wrapperClass = $wrapperClass ?? 'd-flex justify-content-between align-items-center flex-wrap mb-3 index-page-header';
@endphp

@once
    @push('styles')
        <style>
            .index-page-header {
                gap: 1rem;
            }

            .index-page-actions {
                gap: 0.5rem;
            }

            .index-help-button {
                width: 34px;
                height: 34px;
                padding: 0;
                border-radius: 999px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .index-help-panel {
                border: 1px solid #dbe5f3;
                background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
            }

            @media (max-width: 575.98px) {
                .index-page-header {
                    align-items: stretch !important;
                }

                .index-page-actions {
                    width: 100%;
                    justify-content: flex-start;
                }
            }
        </style>
    @endpush
@endonce

<div class="{{ $wrapperClass }}">
    <h4 class="{{ $headingClass }}">{{ $heading }}</h4>

    <div class="d-flex align-items-center flex-wrap index-page-actions">
        @if ($createRoute && $createLabel)
            <a href="{{ $createRoute }}" class="{{ $createClass }}">
                {{ $createLabel }}
            </a>
        @endif

        <button class="btn btn-outline-primary btn-sm index-help-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}"
            title="{{ $helpTitle }}">
            ?
        </button>
    </div>
</div>

<div class="collapse mb-4" id="{{ $collapseId }}">
    <div class="card shadow-sm border-0 index-help-panel">
        <div class="card-body">
            <h6 class="fw-semibold mb-2">{{ $helpTitle }}</h6>

            @foreach ($messages as $message)
                <p class="text-muted mb-{{ $loop->last ? '0' : '2' }}">
                    {{ $message }}
                </p>
            @endforeach
        </div>
    </div>
</div>
