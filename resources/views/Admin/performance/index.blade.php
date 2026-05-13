@extends('Admin.Template.index')

@section('title', 'Kinerja Tim')

@php
    $dateLabel = $from->format('d M Y') . ' - ' . $to->format('d M Y');
    $formatDate = fn ($date) => $date ? $date->format('d M Y H:i') : '-';
@endphp

@push('styles')
    <style>
        .performance-score {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent);
            color: #fff;
            font-weight: 800;
        }

        .performance-bar {
            height: 8px;
            border-radius: 999px;
            background: var(--accent-soft);
            overflow: hidden;
        }

        .performance-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--accent);
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
                <span class="admin-page-eyebrow">Superadmin</span>
                <h1 class="admin-page-title">Kinerja Tim</h1>
                <p class="admin-page-subtitle">Pantauan kontribusi internal dari activity log admin untuk semua role kantor kecuali user/customer.</p>
            </div>
        </div>

        <details class="context-note mb-4">
            <summary>
                <span>Notes Penilaian Kinerja</span>
                <i class="bi bi-plus-lg"></i>
            </summary>
            <div class="context-note__body">
                <p>Penilaian di halaman ini dihitung dari `activity_logs` internal, bukan dari absensi manual atau KPI bisnis terpisah.</p>
                <ul>
                    <li>`Kontribusi` adalah semua log selain `login`, seperti create, update, publish, dan delete.</li>
                    <li>`Hari aktif` dihitung dari jumlah tanggal unik saat staff punya aktivitas pada periode terpilih.</li>
                    <li>`Login` tetap dihitung, tetapi bobotnya dibatasi agar skor tidak naik hanya karena sering masuk sistem.</li>
                    <li>`Skor` memakai rumus saat ini: `min(100, kontribusi x 6 + hari aktif x 3 + min(login, 20))`.</li>
                    <li>Route, event, device, dan waktu terakhir diambil dari data log yang tersimpan sekarang.</li>
                </ul>
            </div>
        </details>

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
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected($selectedRole === $role->name)>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Dari tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-xl-4 d-flex gap-2 justify-content-xl-end">
                    <a href="{{ route('admin.performance.index') }}" class="btn btn-outline-dark">Reset</a>
                    <button class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Staff terpantau</small>
                        <h3 class="mt-2 mb-1">{{ number_format($summary['staff_count'], 0, ',', '.') }}</h3>
                        <span class="text-muted small">{{ $dateLabel }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Staff aktif</small>
                        <h3 class="mt-2 mb-1">{{ number_format($summary['active_staff_count'], 0, ',', '.') }}</h3>
                        <span class="text-muted small">Memiliki log pada periode ini</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Kontribusi tercatat</small>
                        <h3 class="mt-2 mb-1">{{ number_format($summary['contribution_count'], 0, ',', '.') }}</h3>
                        <span class="text-muted small">Selain login</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body">
                        <small class="text-muted d-block">Rata-rata skor</small>
                        <h3 class="mt-2 mb-1">{{ number_format($summary['average_score'], 1, ',', '.') }}</h3>
                        <span class="text-muted small">Skala aktivitas 0-100</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Peringkat Staff</h5>
                    <p class="text-muted mb-3">Urutan berdasarkan skor aktivitas dan kontribusi pada periode terpilih.</p>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Role</th>
                                    <th class="text-end">Skor</th>
                                    <th class="text-end">Kontribusi</th>
                                    <th class="text-end">Login</th>
                                    <th class="text-end">Hari Aktif</th>
                                    <th>Aktivitas Terakhir</th>
                                    <th class="text-end">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($staffPerformance as $row)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $row['user']->name }}</div>
                                            <small class="text-muted">{{ $row['user']->email }}</small>
                                        </td>
                                        <td>{{ $row['roles']->map(fn ($role) => ucfirst($role))->join(', ') }}</td>
                                        <td class="text-end">
                                            <span class="performance-score">{{ $row['score'] }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($row['contribution_count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['login_count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['active_days'], 0, ',', '.') }}</td>
                                        <td>{{ $formatDate($row['last_activity_at']) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.performance.show', $row['user']) }}" class="btn btn-sm btn-dark">Buka</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Belum ada staff pada filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Ringkasan Role</h5>
                    <p class="text-muted mb-3">Aktivitas agregat per role selain user.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th class="text-end">Member</th>
                                    <th class="text-end">Aktif</th>
                                    <th class="text-end">Kontribusi</th>
                                    <th class="text-end">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roleSummary as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ ucfirst($row['role']->name) }}</td>
                                        <td class="text-end">{{ number_format($row['member_count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['active_member_count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['contribution_count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['average_score'], 1, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada role internal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Jenis Aktivitas</h5>
                    <p class="text-muted mb-3">Distribusi event pada periode terpilih.</p>

                    <div class="d-flex flex-column gap-3">
                        @forelse ($eventSummary as $row)
                            @php
                                $maxEvent = max((int) $eventSummary->max('count'), 1);
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between gap-2 mb-2">
                                    <span class="fw-semibold">{{ $row['label'] }}</span>
                                    <span class="text-muted">{{ number_format($row['count'], 0, ',', '.') }}</span>
                                </div>
                                <div class="performance-bar">
                                    <span style="width: {{ min(100, ($row['count'] / $maxEvent) * 100) }}%;"></span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Belum ada aktivitas pada periode ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Aktivitas Terbaru</h5>
                    <p class="text-muted mb-3">Log terbaru dari semua role internal pada filter saat ini.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Staff</th>
                                    <th>Event</th>
                                    <th>Route</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentActivities as $log)
                                    <tr>
                                        <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                        <td>{{ $log->user->name ?? '-' }}</td>
                                        <td><span class="badge bg-dark">{{ $eventLabels[$log->event] ?? str_replace('_', ' ', $log->event) }}</span></td>
                                        <td>{{ data_get($log->new_values, 'route') ?: ($log->model_type ? class_basename($log->model_type) : '-') }}</td>
                                        <td>{{ $log->ip_address ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
