@extends('Users.Template.index')

@section('title', 'Account')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Libre Franklin', sans-serif;
        }

        /* LAYOUT */
        .page-wrapper {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        /* LEFT PANEL */
        .left-panel {
            width: 50%;
            position: sticky;
            top: 80px;
            height: calc(100vh - 80px);
        }

        .left-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* RIGHT PANEL */
        .right-panel {
            width: 50%;
            background: rgb(255, 255, 255);
            padding: 60px;
        }

        .welcome-heading {
            font-size: 40px;
            font-weight: 300;
            margin-bottom: 30px;
        }

        /* TABS */
        .account-tabs {
            display: flex;
            gap: 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .account-tab {
            font-size: 14px;
            color: #999;
            cursor: pointer;
            padding-bottom: 10px;
            border-bottom: 2px solid transparent;
        }

        .account-tab.active {
            color: #000;
            border-bottom: 2px solid #000;
        }

        /* PROFILE */
        .profile-section {
            background: rgba(228, 228, 228, 0.21);
            padding: 20px;
            margin-bottom: 6px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 500;
        }

        .edit-btn {
            font-size: 12px;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* FIELD */
        .field-row {
            margin-bottom: 12px;
        }

        .field-label {
            font-size: 11px;
            color: #999;
        }

        .field-value {
            font-size: 14px;
        }

        /* SIDEBAR */
        .edit-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            opacity: 0;
            pointer-events: none;
            transition: 0.3s;
            z-index: 999;
        }

        .edit-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .edit-sidebar {
            position: fixed;
            top: 0;
            right: -480px;
            width: 480px;
            height: 100vh;
            background: #fff;
            z-index: 1000;
            transition: 0.35s;
            display: flex;
            flex-direction: column;
        }

        .edit-sidebar form {
            display: flex;
            flex: 1;
            flex-direction: column;
            min-height: 0;
        }

        .edit-sidebar.open {
            right: 0;
        }

        .edit-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .edit-sidebar-body {
            padding: 20px;
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .edit-sidebar-footer {
            padding: 20px;
            flex-shrink: 0;
            border-top: 1px solid #eee;
        }

        /* FORM */
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        /* BUTTON */
        .save-btn {
            width: 100%;
            padding: 14px;
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-wrapper {
                flex-direction: column;
            }

            .left-panel {
                width: 100%;
                height: 200px;
                position: relative;
                top: 0;
            }

            .left-image {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

            .right-panel {
                width: 100%;
                padding: 300px 20px 20px;
            }
        }

        /* ADDRESS CARD */
        .addr-card {
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            padding: 16px 18px;
            margin-bottom: 12px;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s ease;
        }

        .addr-card:hover {
            border-color: #999;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        /* TOP */
        .addr-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .addr-card-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* BADGE */
        .addr-primary-badge {
            font-size: 10px;
            font-weight: 600;
            background: #000;
            color: #fff;
            padding: 3px 8px;
            border-radius: 2px;
            text-transform: uppercase;
        }

        /* BUTTON SET PRIMARY */
        .addr-set-primary {
            font-size: 11px;
            border: 1px solid #ddd;
            background: none;
            padding: 3px 8px;
            cursor: pointer;
            border-radius: 2px;
            transition: all 0.2s;
        }

        .addr-set-primary:hover {
            border-color: #000;
            color: #000;
        }

        /* RECIPIENT */
        .addr-card-recipient {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        /* ADDRESS TEXT */
        .addr-card-full {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 4px;
        }

        /* NOTE */
        .addr-card-note {
            font-size: 11px;
            color: #888;
            margin-bottom: 4px;
        }

        /* PINPOINT */
        .addr-card-pinpoint {
            font-size: 11px;
            color: #999;
            margin-bottom: 6px;
        }

        /* EDIT HINT */
        .addr-card-edit-hint {
            font-size: 10px;
            color: #bbb;
            text-align: right;
        }

        .addr-card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 10px;
        }

        .addr-card-action {
            border: 1px solid #ddd;
            background: #fff;
            color: #666;
            padding: 6px 10px;
            font-size: 10px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .addr-card-action:hover {
            border-color: #000;
            color: #000;
        }

        .addr-card-action.danger {
            border-color: #e5c3c3;
            color: #9b2c2c;
        }

        .addr-card-action.danger:hover {
            border-color: #9b2c2c;
            color: #9b2c2c;
        }

        /* SIDEBAR HEADER */
        .edit-sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        /* BODY SCROLL */
        .edit-sidebar-body {
            padding: 16px;
            overflow-y: auto;
        }

        /* FOOTER */
        .edit-sidebar-footer {
            padding: 16px;
            border-top: 1px solid #eee;
        }

        /* BUTTON */
        .save-btn {
            width: 100%;
            padding: 14px;
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 13px;
            letter-spacing: 1px;
            transition: 0.2s;
        }

        .save-btn:hover {
            background: #333;
        }

        .sidebar-close {
            border: none;
            background: transparent;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
        }

        .sidebar-actions {
            display: flex;
            gap: 12px;
        }

        .secondary-btn {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            background: #fff;
            color: #111;
            cursor: pointer;
            font-size: 13px;
        }

        .danger-btn {
            border-color: #d8b1b1;
            color: #8f2121;
        }

        .address-count {
            font-size: 11px;
            color: #7a7a7a;
            margin-bottom: 10px;
        }

        .address-limit-note {
            font-size: 11px;
            color: #8a8a8a;
            text-align: center;
            margin-top: 10px;
        }

        .save-btn[disabled],
        .secondary-btn[disabled] {
            cursor: not-allowed;
            opacity: 0.45;
        }

        .location-group {
            margin-top: 6px;
        }

        .location-label {
            font-size: 11px;
            color: #888;
            margin-bottom: 8px;
        }

        .location-helper {
            font-size: 11px;
            color: #7a7a7a;
            line-height: 1.5;
            margin: 8px 0 10px;
        }

        .location-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .location-meta-box {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 10px 12px;
            background: #fafafa;
        }

        .location-meta-label {
            font-size: 10px;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .location-meta-value {
            font-size: 12px;
            color: #111;
            word-break: break-word;
        }

        .map-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }

        .map-action-btn {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #ddd;
            background: #fff;
            color: #111;
            font-size: 12px;
            cursor: pointer;
        }

        .address-map {
            width: 100%;
            height: 260px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 6px;
            background: #f6f6f6;
            touch-action: pan-x pan-y;
        }

        .addr-card-coordinates {
            font-size: 10px;
            color: #9a9a9a;
        }

        .form-input[readonly] {
            background: #f8f8f8;
            color: #666;
        }

        @media (max-width: 768px) {
            .location-meta {
                grid-template-columns: 1fr;
            }

            .map-actions {
                flex-direction: column;
            }
        }

        .signout-btn {
            display: inline-block;
            color: #c00;
            font-size: 13px;
            border: 1px solid #c00;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .signout-btn:hover {
            background: #c00;
            color: #fff;
        }

        .signout-btn::after {
            content: '›';
            font-size: 16px;
        }

        .account-link-btn {
            display: inline-block;
            color: #111;
            font-size: 13px;
            border: 1px solid #111;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .account-link-btn:hover {
            background: #111;
            color: #fff;
        }

        .account-link-btn::after {
            content: '›';
            font-size: 16px;
        }
    </style>
@endpush


@section('content')

    @php($formErrors = $errors ?? new \Illuminate\Support\ViewErrorBag())

    <div class="page-wrapper">

        {{-- LEFT --}}
        <div class="left-panel">
            <img class="left-image" src="{{ asset('storage/' . $display->first()->image_path) }}">
        </div>

        {{-- RIGHT --}}
        <div class="right-panel">
            <h1 class="welcome-heading">Welcome,<br>{{ $user->name ?? 'User' }}</h1>

            {{-- BASIC --}}
            <div class="profile-section" id="profileInfoSection">
                <div class="section-header">
                    <span class="section-title">Basic Information</span>
                    <button type="button" class="edit-btn" onclick="openSidebar('basicSidebar')">
                        Edit ›
                    </button>
                </div>

                {{-- FIRST NAME --}}
                <div class="field-row">
                    <div class="field-label"> Name</div>
                    <div class="field-value">
                        {{ $user->name ?? '-' }}
                    </div>
                </div>


                {{-- EMAIL --}}
                <div class="field-row">
                    <div class="field-label">Email</div>
                    <div class="field-value">
                        {{ $user->email }}
                    </div>
                </div>

                {{-- PHONE --}}
                <div class="field-row">
                    <div class="field-label">Phone Number</div>
                    <div class="field-value">
                        {{ $user->phone ?? '-' }}
                    </div>
                </div>

                {{-- GENDER --}}
                <div class="field-row">
                    <div class="field-label">Gender</div>
                    <div class="field-value">
                        {{ $user->gender ?? '-' }}
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-label">Date of Birth</div>
                    <div class="field-value">
                        {{ $user->date_of_birth ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- ADDRESS --}}
            <div class="profile-section" id="addressPreviewSection">
                <div class="section-header">
                    <span class="section-title">Address</span>
                    <button type="button" class="edit-btn" onclick="openSidebar('addressSidebar')">
                        Edit ›
                    </button>
                </div>

                @if ($address)
                    <div class="field-row">
                        <div class="field-label">Label</div>
                        <div class="field-value">
                            {{ $address->label }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Pinpoint</div>
                        <div class="field-value">
                            {{ trim(($address->city ?? '') . ', ' . ($address->province ?? ''), ', ') ?: '-' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Alamat Lengkap</div>
                        <div class="field-value">
                            {{ $address->full_address }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Catatan Kurir</div>
                        <div class="field-value">
                            {{ $address->note ?: '-' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Nama Penerima</div>
                        <div class="field-value">
                            {{ $address->recipient_name }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">No. HP</div>
                        <div class="field-value">
                            {{ $address->phone_number }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Titik Posisi</div>
                        <div class="field-value">
                            @if (!is_null($address->latitude) && !is_null($address->longitude))
                                {{ number_format($address->latitude, 7) }}, {{ number_format($address->longitude, 7) }}
                            @else
                                Belum dipilih
                            @endif
                        </div>
                    </div>
                @else
                    <div class="field-row">
                        <div class="field-label">Address</div>
                        <div class="field-value">Belum ada alamat utama.</div>
                    </div>
                @endif
            </div>
            <div class="profile-section">
                <div class="section-header">
                    <span class="section-title">Purchase History</span>
                </div>
                <a href="{{ route('user.orders.index') }}" class="account-link-btn">
                    Lihat Riwayat Pembelian
                </a>
            </div>
            <div class="profile-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="signout-btn">Logout</button>
                </form>
            </div>
        </div>

    </div>


    {{-- OVERLAY --}}
    <div class="edit-overlay" id="overlay"></div>

    {{-- BASIC SIDEBAR --}}
    <div class="edit-sidebar" id="basicSidebar">
        <div class="edit-sidebar-header">
            <b>Edit Profile</b>
            <button type="button" class="sidebar-close" onclick="closeSidebar()">×</button>
        </div>

        <form method="POST" action="{{ route('account.update') }}">
            @csrf
            @method('PUT')

            <div class="edit-sidebar-body">
                <input type="text" name="name" class="form-input" placeholder="Nama"
                    value="{{ old('name', $user->name) }}">
                <input type="email" class="form-input" value="{{ $user->email }}" readonly>
                <input type="text" name="phone" class="form-input" placeholder="No. HP"
                    value="{{ old('phone', $user->phone) }}">

                <select name="gender" class="form-input">
                    <option value="">Pilih Gender</option>
                    <option value="pria" @selected(old('gender', $user->gender) === 'pria')>Pria</option>
                    <option value="wanita" @selected(old('gender', $user->gender) === 'wanita')>Wanita</option>
                </select>

                <input type="date" name="date_of_birth" class="form-input" placeholder="Tanggal Lahir"
                    value="{{ old('date_of_birth', $user->date_of_birth) }}">
            </div>

            <div class="edit-sidebar-footer">
                <button type="submit" class="save-btn">Simpan Profil</button>
            </div>
        </form>
    </div>
    <div class="edit-sidebar" id="addressFormSidebar">
        <div class="edit-sidebar-header">
            <b id="formTitle">Tambah Alamat</b>
            <button type="button" class="sidebar-close" onclick="closeSidebar()">×</button>
        </div>

        <form method="POST" id="addressForm">
            @csrf
            <div class="edit-sidebar-body">
                <input type="text" name="label" placeholder="Label" class="form-input">
                <input type="text" name="recipient_name" placeholder="Nama Penerima" class="form-input">
                <input type="text" name="phone_number" placeholder="No HP" class="form-input">
                <input type="text" name="city" placeholder="Kota" class="form-input">
                <input type="text" name="province" placeholder="Provinsi" class="form-input">
                <textarea name="full_address" placeholder="Alamat Lengkap" class="form-input"></textarea>
                <input type="text" name="postal_code" placeholder="Kode Pos" class="form-input">
                <input type="text" name="note" placeholder="Catatan" class="form-input">
                <input type="hidden" name="latitude">
                <input type="hidden" name="longitude">

                <div class="location-group">
                    <div class="location-label">Titik Lokasi</div>
                    <div class="location-helper">
                        Klik peta untuk memilih posisi alamat atau geser marker untuk menyesuaikan titik.
                    </div>

                    <div class="location-meta">
                        <div class="location-meta-box">
                            <div class="location-meta-label">Latitude</div>
                            <div class="location-meta-value" id="latitudeValue">Belum dipilih</div>
                        </div>
                        <div class="location-meta-box">
                            <div class="location-meta-label">Longitude</div>
                            <div class="location-meta-value" id="longitudeValue">Belum dipilih</div>
                        </div>
                    </div>

                    <div class="map-actions">
                        <button type="button" class="map-action-btn" onclick="focusCurrentLocation()">
                            Gunakan Lokasi Saya
                        </button>
                        <button type="button" class="map-action-btn" onclick="clearAddressPoint()">
                            Reset Titik
                        </button>
                    </div>

                    <div id="addressMap" class="address-map"></div>
                </div>

            </div>

            <div class="edit-sidebar-footer">
                <div class="sidebar-actions">
                    <button type="button" id="addressDeleteBtn" class="secondary-btn danger-btn"
                        onclick="handleDeleteCurrentAddress()" style="display: none;">
                        Hapus
                    </button>
                    <button type="submit" class="save-btn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    {{-- ADDRESS SIDEBAR --}}
    <div class="edit-sidebar" id="addressSidebar">
        <div class="edit-sidebar-header">
            <b>Address</b>
            <button type="button" class="sidebar-close" onclick="closeSidebar()">×</button>
        </div>

        <div class="edit-sidebar-body" id="addressSidebarBody">

            @forelse($addresses as $addr)
                <div class="addr-card" onclick="editAddress({{ $addr->id }})">

                    <div class="addr-card-top">
                        <div class="addr-card-label">
                            {{ $addr->label }}
                        </div>

                        @if ($addr->is_primary)
                            <span class="addr-primary-badge">Utama</span>
                        @else
                            <button type="button" class="addr-set-primary"
                                onclick="event.stopPropagation(); setPrimary({{ $addr->id }})">
                                Jadikan Utama
                            </button>
                        @endif
                    </div>

                    <div class="addr-card-recipient">
                        {{ $addr->recipient_name }}
                        @if ($addr->phone_number)
                            · {{ $addr->phone_number }}
                        @endif
                    </div>

                    <div class="addr-card-full">
                        {{ $addr->full_address }}
                    </div>

                    @if ($addr->note)
                        <div class="addr-card-note">
                            {{ $addr->note }}
                        </div>
                    @endif

                    <div class="addr-card-pinpoint">
                        ● {{ $addr->city }}, {{ $addr->province }}
                    </div>

                    @if (!is_null($addr->latitude) && !is_null($addr->longitude))
                        <div class="addr-card-coordinates">
                            {{ number_format($addr->latitude, 7) }}, {{ number_format($addr->longitude, 7) }}
                        </div>
                    @endif

                    <div class="addr-card-actions">
                        <button type="button" class="addr-card-action"
                            onclick="event.stopPropagation(); editAddress({{ $addr->id }})">
                            Edit
                        </button>
                        <button type="button" class="addr-card-action danger"
                            onclick="event.stopPropagation(); deleteAddress({{ $addr->id }})">
                            Hapus
                        </button>
                    </div>

                    <div class="addr-card-edit-hint">
                        Ketuk kartu untuk edit cepat
                    </div>
                </div>

            @empty
                <div style="text-align:center; padding:40px; color:#999;">
                    Belum ada alamat
                </div>
            @endforelse

        </div>

        <div class="edit-sidebar-footer">
            <div class="address-count" id="addressCountInfo">
                {{ $addresses->count() }}/3 alamat tersimpan
            </div>
            <button type="button" onclick="openCreateForm()" class="save-btn" id="addressAddBtn">
                + Tambah Alamat
            </button>
            <div class="address-limit-note" id="addressLimitNote">
                Maksimal 3 alamat per akun
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const csrf = '{{ csrf_token() }}';
        const addressBaseUrl = '{{ url('/address') }}';
        const accountUrl = '{{ route('account.index') }}';
        const shouldOpenProfileSidebar = @json($formErrors->any());
        const maxAddresses = 3;
        const defaultMapCenter = [-6.2000000, 106.8166660];
        const defaultMapZoom = 12;
        let addressMap = null;
        let addressMarker = null;

        const notifySuccess = (message, title = 'Berhasil') => {
            if (window.appNotify?.success) {
                window.appNotify.success(message, title);
                return;
            }

            if (window.appDialog?.alert) {
                window.appDialog.alert(message, title);
                return;
            }
        };
        const notifyError = (message, title = 'Gagal') => {
            if (window.appNotify?.error) {
                window.appNotify.error(message, title);
                return;
            }

            if (window.appDialog?.alert) {
                window.appDialog.alert(message, title, {
                    variant: 'danger'
                });
            }
        };

        function extractErrorMessage(payload, fallback) {
            if (window.appNotify?.extractMessage) {
                return window.appNotify.extractMessage(payload, fallback);
            }

            return fallback;
        }

        async function parseJsonSafely(response) {
            try {
                return await response.json();
            } catch (error) {
                return null;
            }
        }

        function getAddressCount() {
            return document.querySelectorAll('#addressSidebarBody .addr-card').length;
        }

        function updateAddressLimitUI() {
            const count = getAddressCount();
            const addButton = document.getElementById('addressAddBtn');
            const countInfo = document.getElementById('addressCountInfo');
            const limitNote = document.getElementById('addressLimitNote');
            const isDisabled = count >= maxAddresses;

            if (countInfo) {
                countInfo.textContent = `${count}/${maxAddresses} alamat tersimpan`;
            }

            if (limitNote) {
                limitNote.textContent = isDisabled ?
                    'Batas alamat sudah tercapai. Hapus salah satu alamat untuk menambah baru.' :
                    'Maksimal 3 alamat per akun';
            }

            if (addButton) {
                addButton.disabled = isDisabled;
            }
        }

        function getAddressForm() {
            return document.getElementById('addressForm');
        }

        function getCoordinateDisplayElements() {
            return {
                latitude: document.getElementById('latitudeValue'),
                longitude: document.getElementById('longitudeValue'),
            };
        }

        function updateCoordinateDisplay(lat = '', lng = '') {
            const display = getCoordinateDisplayElements();

            if (display.latitude) {
                display.latitude.textContent = lat || 'Belum dipilih';
            }

            if (display.longitude) {
                display.longitude.textContent = lng || 'Belum dipilih';
            }
        }

        function ensureAddressMap() {
            if (typeof L === 'undefined') {
                notifyError('Leaflet gagal dimuat.', 'Alamat');
                return false;
            }

            if (!addressMap) {
                addressMap = L.map('addressMap').setView(defaultMapCenter, defaultMapZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(addressMap);

                addressMap.on('click', (event) => {
                    setAddressPoint(event.latlng.lat, event.latlng.lng, true);
                });
            }

            window.setTimeout(() => {
                addressMap.invalidateSize();
            }, 180);

            return true;
        }

        function removeAddressMarker() {
            if (addressMarker && addressMap) {
                addressMap.removeLayer(addressMarker);
                addressMarker = null;
            }
        }

        function setAddressPoint(lat, lng, recenter = false) {
            const form = getAddressForm();
            const parsedLat = Number(lat);
            const parsedLng = Number(lng);

            if (!form || Number.isNaN(parsedLat) || Number.isNaN(parsedLng) || !ensureAddressMap()) {
                return;
            }

            const fixedLat = parsedLat.toFixed(7);
            const fixedLng = parsedLng.toFixed(7);

            form.latitude.value = fixedLat;
            form.longitude.value = fixedLng;
            updateCoordinateDisplay(fixedLat, fixedLng);

            if (!addressMarker) {
                addressMarker = L.marker([parsedLat, parsedLng], {
                    draggable: true
                }).addTo(addressMap);

                addressMarker.on('dragend', (event) => {
                    const markerPoint = event.target.getLatLng();
                    setAddressPoint(markerPoint.lat, markerPoint.lng);
                });
            } else {
                addressMarker.setLatLng([parsedLat, parsedLng]);
            }

            if (recenter) {
                addressMap.setView([parsedLat, parsedLng], Math.max(addressMap.getZoom(), 16));
            }
        }

        function clearAddressPoint() {
            const form = getAddressForm();

            if (!form) {
                return;
            }

            form.latitude.value = '';
            form.longitude.value = '';
            updateCoordinateDisplay();
            removeAddressMarker();

            if (addressMap) {
                addressMap.setView(defaultMapCenter, defaultMapZoom);
            }
        }

        function syncAddressMap(lat = null, lng = null) {
            if (!ensureAddressMap()) {
                return;
            }

            if (lat === null || lng === null || lat === '' || lng === '') {
                clearAddressPoint();
                return;
            }

            setAddressPoint(lat, lng, true);
        }

        function focusCurrentLocation() {
            if (!navigator.geolocation) {
                notifyError('Browser tidak mendukung geolokasi.', 'Alamat');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    setAddressPoint(position.coords.latitude, position.coords.longitude, true);
                    notifySuccess('Posisi saat ini berhasil dipilih.', 'Alamat');
                },
                () => {
                    notifyError('Lokasi saat ini tidak bisa diakses.', 'Alamat');
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                }
            );
        }

        function openSidebar(id) {
            closeSidebar();
            document.getElementById(id).classList.add('open');
            document.getElementById('overlay').classList.add('open');

            if (id === 'addressFormSidebar') {
                ensureAddressMap();
            }
        }

        function closeSidebar() {
            document.querySelectorAll('.edit-sidebar').forEach(s => s.classList.remove('open'));
            document.getElementById('overlay').classList.remove('open');
        }

        document.getElementById('overlay').onclick = closeSidebar;

        if (shouldOpenProfileSidebar) {
            openSidebar('basicSidebar');
        }

        updateAddressLimitUI();

        function openCreateForm() {
            if (getAddressCount() >= maxAddresses) {
                notifyError('Maksimal 3 alamat per akun.', 'Alamat');
                return;
            }

            const form = document.getElementById('addressForm');
            const deleteButton = document.getElementById('addressDeleteBtn');

            form.reset();
            form.dataset.mode = 'create';
            form.dataset.id = '';
            deleteButton.style.display = 'none';

            document.getElementById('formTitle').innerText = "Tambah Alamat";

            openSidebar('addressFormSidebar');
            clearAddressPoint();
        }


        function editAddress(id) {
            fetch(`${addressBaseUrl}/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal fetch data');
                    return res.json();
                })
                .then(data => {
                    const form = document.getElementById('addressForm');
                    const deleteButton = document.getElementById('addressDeleteBtn');

                    form.dataset.mode = 'edit';
                    form.dataset.id = id;

                    form.label.value = data.label || '';
                    form.recipient_name.value = data.recipient_name || '';
                    form.phone_number.value = data.phone_number || '';
                    form.city.value = data.city || '';
                    form.province.value = data.province || '';
                    form.full_address.value = data.full_address || '';
                    form.postal_code.value = data.postal_code || '';
                    form.note.value = data.note || '';
                    deleteButton.style.display = 'block';

                    document.getElementById('formTitle').innerText = "Edit Alamat";

                    openSidebar('addressFormSidebar');
                    syncAddressMap(data.latitude, data.longitude);
                })
                .catch(err => {
                    console.error(err);
                    notifyError('Data alamat gagal diambil.', 'Alamat');
                });
        }

        function handleDeleteCurrentAddress() {
            const form = document.getElementById('addressForm');

            if (!form.dataset.id) {
                return;
            }

            deleteAddress(form.dataset.id);
        }

        document.getElementById('addressForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const data = new FormData(form);

            let url = addressBaseUrl;

            if (form.dataset.mode === 'edit') {
                url = `${addressBaseUrl}/${form.dataset.id}`;
                data.append('_method', 'PUT');
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: data
                });

                if (!res.ok) {
                    const err = await parseJsonSafely(res);
                    console.error(err);
                    notifyError(extractErrorMessage(err, 'Alamat gagal disimpan.'), 'Alamat');
                    return;
                }

                const result = await parseJsonSafely(res);

                closeSidebar();
                await reloadAddressUI();
                notifySuccess(result?.message || 'Alamat berhasil disimpan.', 'Alamat');

            } catch (err) {
                console.error(err);
                notifyError('Alamat gagal disimpan.', 'Alamat');
            }
        });


        async function deleteAddress(id) {
            const confirmed = await window.appDialog.confirm(
                'Alamat ini akan dihapus secara permanen.',
                'Hapus Alamat', {
                    confirmText: 'Hapus',
                    variant: 'danger'
                }
            );

            if (!confirmed) return;

            try {
                const res = await fetch(`${addressBaseUrl}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    throw new Error();
                }

                const result = await parseJsonSafely(res);
                closeSidebar();
                await reloadAddressUI();
                notifySuccess(result?.message || 'Alamat berhasil dihapus.', 'Alamat');
            } catch (err) {
                notifyError('Alamat gagal dihapus.', 'Alamat');
            }
        }


        function setPrimary(id) {
            fetch(`${addressBaseUrl}/${id}/primary`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    if (!res.ok) throw new Error();
                    const result = await parseJsonSafely(res);
                    notifySuccess(result?.message || 'Alamat utama berhasil diperbarui.', 'Alamat');
                    return reloadAddressUI();
                })
                .catch(() => notifyError('Alamat utama gagal diperbarui.', 'Alamat'));
        }


        async function reloadAddressUI() {
            const res = await fetch(accountUrl, {
                headers: {
                    'Accept': 'text/html'
                }
            });
            const html = await res.text();

            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newSidebar = doc.getElementById('addressSidebarBody');
            const newPreview = doc.getElementById('addressPreviewSection');

            if (!newSidebar || !newPreview) {
                window.location.reload();
                return;
            }

            document.getElementById('addressSidebarBody').innerHTML = newSidebar.innerHTML;
            document.getElementById('addressPreviewSection').innerHTML = newPreview.innerHTML;
            updateAddressLimitUI();
        }
    </script>
@endpush
