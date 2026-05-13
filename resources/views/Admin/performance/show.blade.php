@extends('Admin.Template.index')

@section('title', 'Detail Kinerja ' . $staff->name)

@php
    $formatDate = fn ($date) => $date ? $date->format('d M Y H:i') : '-';
    $dateLabel = $from->format('d M Y') . ' - ' . $to->format('d M Y');
@endphp

@push('styles')
    <style>
        .performance-score-large {
            width: 78px;
            height: 78px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent);
            color: #fff;
            font-size: 1.65rem;
            font-weight: 800;
        }

        .daily-activity-cell {
            min-width: 42px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-soft);
            font-weight: 700;
        }

        .context-note {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface-strong);
        }

        .context-note summary {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
            padding: 18px 20px;
            font-weight: 600;
        }

        .context-note summary::-webkit-details-marker {
            display: none;
        }

        .context-note__body {
            padding: 0 20px 20px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .context-note__body p:last-child,
        .context-note__body ul:last-child {
            margin-bottom: 0;
        }

        .context-note__body ul {
            margin: 12px 0 0;
            padding-left: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Kinerja Staff</span>
                <h1 class="admin-page-title">{{ $staff->name }}</h1>
                <p class="admin-page-subtitle">{{ $staff->email }} · {{ $staff->roles->pluck('name')->reject(fn ($role) => $role === 'user')->map(fn ($role) => ucfirst($role))->join(', ') }}</p>
            </div>

            <div class="admin-page-actions">
                <a href="{{ route('admin.performance.index', request()->only(['days', 'date_from', 'date_to', 'role'])) }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card p-3 p-lg-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Periode cepat</label>
                    <select name="days" class="form-select">
                        @foreach ([7, 30, 90, 180] as $option)
                            <option value="{{ $option }}" @selected((int) request('days', $days) === $option)>{{ $option }} hari</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label">Dari tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-xl-4 d-flex gap-2 justify-content-xl-end">
                    <a href="{{ route('admin.performance.show', $staff) }}" class="btn btn-outline-dark">Reset</a>
                    <button class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>

        <details class="context-note mb-4">
            <summary>
                <span>Catatan Detail Penilaian</span>
                <i class="bi bi-plus-lg"></i>
            </summary>
            <div class="context-note__body">
                <p>Halaman ini memecah skor staff menjadi aktivitas harian, jenis event, area kerja, dan perangkat yang terekam di log.</p>
                <ul>
                    <li>`Kontribusi` hanya menghitung aksi kerja, bukan login.</li>
                    <li>`Area kerja` berasal dari `new_values.route` pada log admin, jadi hasilnya mengikuti route yang benar-benar disentuh.</li>
                    <li>`Perangkat` berasal dari kombinasi device, browser, dan platform yang tersimpan saat aksi terjadi.</li>
                    <li>Jika ada aksi yang tidak tercatat di log, halaman ini memang tidak akan menghitungnya.</li>
                </ul>
            </div>
        </details>

        <div class="row g-4 mb-4">
            <div class="col-xl-4">
                <div class="card h-100 p-3 p-lg-4">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Skor aktivitas</small>
                            <h5 class="mt-2 mb-1">{{ $dateLabel }}</h5>
                            <span class="text-muted small">Skala 0-100</span>
                        </div>
                        <span class="performance-score-large">{{ $row['score'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Kontribusi</small>
                        <h3 class="mt-2 mb-0">{{ number_format($row['contribution_count'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Login</small>
                        <h3 class="mt-2 mb-0">{{ number_format($row['login_count'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Hari aktif</small>
                        <h3 class="mt-2 mb-0">{{ number_format($row['active_days'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Total log</small>
                        <h3 class="mt-2 mb-0">{{ number_format($row['activity_count'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Aktivitas Harian</h5>
                    <p class="text-muted mb-3">Jumlah kontribusi dan login per hari pada periode terpilih.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-center">Kontribusi</th>
                                    <th class="text-center">Login</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dailyActivity as $day)
                                    <tr>
                                        <td class="fw-semibold">{{ $day['date']->format('d M Y') }}</td>
                                        <td class="text-center"><span class="daily-activity-cell">{{ $day['contribution_count'] }}</span></td>
                                        <td class="text-center"><span class="daily-activity-cell">{{ $day['login_count'] }}</span></td>
                                        <td class="text-end">{{ number_format($day['activity_count'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas harian.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Jenis Aktivitas</h5>
                    <p class="text-muted mb-3">Breakdown event untuk staff ini.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($eventSummary as $event)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $event['label'] }}</span></td>
                                        <td class="text-end">{{ number_format($event['count'], 0, ',', '.') }}</td>
                                        <td>{{ $formatDate($event['last_at']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada event.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Area Kerja</h5>
                    <p class="text-muted mb-3">Route admin yang paling sering disentuh.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($routeSummary as $route)
                                    <tr>
                                        <td class="fw-semibold">{{ $route['route'] }}</td>
                                        <td class="text-end">{{ number_format($route['count'], 0, ',', '.') }}</td>
                                        <td>{{ $formatDate($route['last_at']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada route kontribusi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Perangkat</h5>
                    <p class="text-muted mb-3">Device dan browser yang tercatat di activity log.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Device / Browser</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($deviceSummary as $device)
                                    <tr>
                                        <td class="fw-semibold">{{ $device['device'] }}</td>
                                        <td class="text-end">{{ number_format($device['count'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Belum ada data perangkat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3 p-lg-4">
            <h5 class="mb-1">Riwayat Detail</h5>
            <p class="text-muted mb-3">Log lengkap staff pada periode ini.</p>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Event</th>
                            <th>Route/Model</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>IP</th>
                            <th>Platform</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                <td><span class="badge bg-dark">{{ $eventLabels[$log->event] ?? str_replace('_', ' ', $log->event) }}</span></td>
                                <td>{{ data_get($log->new_values, 'route') ?: ($log->model_type ? class_basename($log->model_type) : '-') }}</td>
                                <td>{{ data_get($log->new_values, 'method') ?: '-' }}</td>
                                <td>{{ data_get($log->new_values, 'status_code') ?: '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ trim(($log->device ?: '-') . ' / ' . ($log->browser ?: '-') . ' / ' . ($log->platform ?: '-')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat detail.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
