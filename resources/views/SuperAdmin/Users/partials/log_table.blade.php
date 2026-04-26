@forelse ($logs as $log)
    <tr>
        <td>
            {{ $log->user?->name ?? '-' }} <br>
            <small class="text-muted">{{ $log->user?->email ?? '' }}</small>
        </td>

        <td>
            @php $device = strtolower($log->device); @endphp
            @if (str_contains($device, 'iphone') || str_contains($device, 'android'))
                <i class="bi bi-phone"></i>
            @else
                <i class="bi bi-laptop"></i>
            @endif
            {{ $log->device ?? '-' }}
        </td>

        <td>{{ $log->platform ?? '-' }}</td>

        <td>
            @php $browser = strtolower($log->browser); @endphp
            @if (str_contains($browser, 'chrome'))
                <i class="bi bi-browser-chrome"></i>
            @elseif(str_contains($browser, 'firefox'))
                <i class="bi bi-browser-firefox"></i>
            @elseif(str_contains($browser, 'edge'))
                <i class="bi bi-browser-edge"></i>
            @elseif(str_contains($browser, 'safari'))
                <i class="bi bi-browser-safari"></i>
            @else
                <i class="bi bi-globe"></i>
            @endif
            {{ $log->browser ?? '-' }}
        </td>

        <td>{{ $log->ip_address }}</td>
        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">
            Tidak ada data login.
        </td>
    </tr>
@endforelse
