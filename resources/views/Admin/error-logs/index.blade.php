@extends('Admin.Template.index')

@section('title', 'Error Logs')

@php
    $formatBytes = function ($bytes) {
        $bytes = (float) $bytes;
        $units = ['B', 'KB', 'MB', 'GB'];

        foreach ($units as $unit) {
            if ($bytes < 1024 || $unit === 'GB') {
                return number_format($bytes, $unit === 'B' ? 0 : 1, ',', '.') . ' ' . $unit;
            }

            $bytes /= 1024;
        }
    };

    $levelClass = function ($level) {
        return match ($level) {
            'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR' => 'bg-dark',
            'WARNING' => 'text-bg-warning',
            'NOTICE', 'INFO' => 'text-bg-secondary',
            default => 'text-bg-light',
        };
    };
@endphp

@push('styles')
    <style>
        .log-toolbar {
            border: 1px solid var(--line);
            background: var(--surface-strong);
        }

        .log-list {
            max-height: 68vh;
            overflow: auto;
        }

        .log-item {
            border: 1px solid var(--line);
            border-radius: 14px;
            color: inherit;
            display: block;
            padding: 14px 16px;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .log-item:hover,
        .log-item.is-active {
            border-color: var(--accent);
            box-shadow: var(--shadow-sm);
        }

        .log-message {
            overflow-wrap: anywhere;
        }

        .log-trace {
            background: #111;
            border-radius: 14px;
            color: #f8f8f8;
            font-size: 0.82rem;
            line-height: 1.55;
            margin: 0;
            max-height: 62vh;
            overflow: auto;
            padding: 18px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .log-empty {
            border: 1px dashed var(--line);
            border-radius: 16px;
            padding: 42px 20px;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">System</span>
                <h1 class="admin-page-title">Error Logs</h1>
                <p class="admin-page-subtitle">Pantau error Laravel terbaru dari panel admin tanpa membuka file log lewat editor.</p>
            </div>

            <div class="admin-page-actions">
                @if ($selectedFile)
                    <a href="{{ route('admin.error-logs.download', ['file' => $selectedFile['name']]) }}" class="btn btn-outline-dark">
                        <i class="bi bi-download"></i> Download Log
                    </a>
                @endif
                <a href="{{ route('admin.error-logs.index', request()->only(['file', 'level', 'search'])) }}" class="btn btn-dark">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
            </div>
        </div>

        <div class="card log-toolbar p-3 p-lg-4 mb-4">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Problem</div>
                        <div class="fs-4 fw-semibold">{{ number_format($problemCount, 0, ',', '.') }}</div>
                        <div class="small text-muted">Emergency, alert, critical, dan error.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">Warning</div>
                        <div class="fs-4 fw-semibold">{{ number_format($levelCounts->get('WARNING', 0), 0, ',', '.') }}</div>
                        <div class="small text-muted">Entry warning pada potongan log terbaru.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-muted small">File Aktif</div>
                        <div class="fs-6 fw-semibold text-break">{{ $selectedFile['name'] ?? '-' }}</div>
                        <div class="small text-muted">{{ $selectedFile ? $formatBytes($selectedFile['size']) : 'Tidak ada file log' }}</div>
                    </div>
                </div>
            </div>

            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-3">
                    <label class="form-label">File log</label>
                    <select name="file" class="form-select">
                        @forelse ($files as $file)
                            <option value="{{ $file['name'] }}" @selected(($selectedFile['name'] ?? null) === $file['name'])>
                                {{ $file['name'] }} - {{ $formatBytes($file['size']) }}
                            </option>
                        @empty
                            <option value="">Tidak ada file log</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Level</label>
                    <select name="level" class="form-select">
                        <option value="">Semua level</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}" @selected($selectedLevel === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 col-xl-5">
                    <label class="form-label">Cari pesan / stack trace</label>
                    <input type="search" name="search" class="form-control" value="{{ $search }}" placeholder="Contoh: SQLSTATE, ViewException, route name">
                </div>
                <div class="col-md-4 col-xl-2 d-flex gap-2 justify-content-md-end">
                    <a href="{{ route('admin.error-logs.index') }}" class="btn btn-outline-dark">Reset</a>
                    <button class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card p-3 p-lg-4 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">Daftar Error</h5>
                            <p class="text-muted mb-0">
                                {{ number_format($entries->count(), 0, ',', '.') }} entry ditemukan.
                                @if ($selectedFile)
                                    Dibaca dari {{ $selectedFile['name'] }}.
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($selectedFile)
                        <div class="small text-muted mb-3">
                            File terakhir diubah {{ $selectedFile['modified_at'] }}. Halaman membaca maksimal {{ $formatBytes($readBytes) }} terakhir agar tetap ringan.
                        </div>
                    @endif

                    <div class="log-list d-flex flex-column gap-2">
                        @forelse ($entries as $index => $entry)
                            <a href="{{ route('admin.error-logs.index', array_merge(request()->only(['file', 'level', 'search']), ['entry' => $index])) }}"
                                class="log-item {{ $selectedEntry === $entry ? 'is-active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <span class="badge {{ $levelClass($entry['level']) }}">{{ $entry['level'] }}</span>
                                    <small class="text-muted">{{ $entry['datetime'] }}</small>
                                </div>
                                <div class="fw-semibold log-message">{{ $entry['summary'] }}</div>
                                <div class="small text-muted mt-2">
                                    {{ $entry['environment'] }}
                                    @if ($entry['has_trace'])
                                        <span class="mx-1">/</span> stack trace tersedia
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="log-empty text-muted">
                                <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                                Tidak ada entry log sesuai filter.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card p-3 p-lg-4 h-100">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">Detail Error</h5>
                            <p class="text-muted mb-0">Pesan lengkap dan stack trace dari entry yang dipilih.</p>
                        </div>
                        @if ($selectedEntry)
                            <button type="button" class="btn btn-outline-dark btn-sm" data-copy-log>
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        @endif
                    </div>

                    @if ($selectedEntry)
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge {{ $levelClass($selectedEntry['level']) }}">{{ $selectedEntry['level'] }}</span>
                            <span class="badge text-bg-light">{{ $selectedEntry['environment'] }}</span>
                            <span class="badge text-bg-light">{{ $selectedEntry['datetime'] }}</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Message</label>
                            <div class="border rounded-3 p-3 log-message" data-log-message>{{ $selectedEntry['message'] }}</div>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Stack Trace</label>
                            <pre class="log-trace" data-log-trace>{{ $selectedEntry['trace'] ?: 'Tidak ada stack trace untuk entry ini.' }}</pre>
                        </div>
                    @else
                        <div class="log-empty text-muted">
                            <i class="bi bi-file-earmark-text fs-2 d-block mb-2"></i>
                            Pilih salah satu entry untuk melihat detail.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelector('[data-copy-log]')?.addEventListener('click', async function () {
            const message = document.querySelector('[data-log-message]')?.innerText || '';
            const trace = document.querySelector('[data-log-trace]')?.innerText || '';

            try {
                await navigator.clipboard.writeText(`${message}\n\n${trace}`.trim());
                this.innerHTML = '<i class="bi bi-check2"></i> Copied';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
                }, 1600);
            } catch (error) {
                alert('Browser tidak mengizinkan copy otomatis.');
            }
        });
    </script>
@endpush
