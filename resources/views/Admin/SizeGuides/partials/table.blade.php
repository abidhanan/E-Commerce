<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Type</th>
            <th>Name</th>
            <th>Image</th>
            <th>Sizes</th>
            <th width="20%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sizeGuides as $index => $item)
            <tr>
                <td>{{ $sizeGuides->firstItem() + $index }}</td>

                <td>{{ $item->type }}</td>

                <td>{{ $item->name ?? '-' }}</td>
                <td>
                    @if ($item->img)
                        <img src="{{ asset('storage/' . $item->img) }}" alt="Image" style="height: 50px;">
                    @else
                        <span class="text-muted">No image</span>
                    @endif
                </td>
                <td>
                    {{ count($item->data['sizes'] ?? []) }} size
                </td>

                <td class="d-flex gap-2">
                    <a href="{{ route('admin.size-guides.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('admin.size-guides.destroy', $item->id) }}" method="POST"
                        data-confirm-message="Yakin hapus size guide ini?" data-confirm-title="Hapus Size Guide"
                        data-confirm-button="Hapus" data-confirm-class="btn-danger">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

            {{-- PREVIEW --}}
            <tr>
                <td colspan="5" class="bg-light">
                    @php
                        $sizes = $item->data['sizes'] ?? [];
                    @endphp

                    @if (count($sizes))
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-secondary text-center">
                                    <tr>
                                        <th>Size</th>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($sizes as $size)
                                        @php
                                            $measurements = $size['measurements'] ?? [];
                                            $rowspan = count($measurements);
                                        @endphp

                                        @foreach ($measurements as $indexM => $m)
                                            <tr>

                                                {{-- SIZE (ROWSPAN BIAR GAK NGULANG) --}}
                                                @if ($indexM === 0)
                                                    <td rowspan="{{ $rowspan }}"
                                                        class="text-center align-middle fw-bold">
                                                        {{ $size['size'] ?? '-' }}
                                                    </td>
                                                @endif

                                                {{-- FIELD --}}
                                                <td>
                                                    {{ $m['label'] ?? '-' }}
                                                </td>

                                                {{-- VALUE --}}
                                                <td>
                                                    @if (($m['type'] ?? '') === 'range')
                                                        {{ $m['min'] ?? '-' }} - {{ $m['max'] ?? '-' }}
                                                        {{ $m['unit'] ?? '' }}
                                                    @else
                                                        {{ $m['value'] ?? '-' }} {{ $m['unit'] ?? '' }}
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @else
                        <span class="text-muted">Tidak ada data size</span>
                    @endif
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" class="text-center">
                    Data tidak ditemukan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
