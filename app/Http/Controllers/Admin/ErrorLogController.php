<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ErrorLogController extends Controller
{
    private const READ_BYTES = 2097152;
    private const ENTRY_LIMIT = 250;

    public function index(Request $request)
    {
        $files = $this->logFiles();
        $selectedFile = $this->selectedFile($request, $files);
        $level = Str::upper((string) $request->query('level'));
        $search = trim((string) $request->query('search'));

        $allEntries = $selectedFile
            ? $this->parseEntries($selectedFile['path'])
            : collect();

        $levelCounts = $allEntries->countBy('level');
        $problemCount = $allEntries
            ->whereIn('level', ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR'])
            ->count();

        $entries = $this->filterEntries($allEntries, $level, $search)
            ->values();

        $selectedEntry = $entries->get((int) $request->query('entry', 0));

        return view('Admin.error-logs.index', [
            'files' => $files,
            'selectedFile' => $selectedFile,
            'levels' => ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'DEBUG'],
            'selectedLevel' => $level,
            'search' => $search,
            'levelCounts' => $levelCounts,
            'problemCount' => $problemCount,
            'entries' => $entries,
            'selectedEntry' => $selectedEntry,
            'readBytes' => self::READ_BYTES,
        ]);
    }

    public function download(Request $request): BinaryFileResponse
    {
        $files = $this->logFiles();
        $selectedFile = $this->selectedFile($request, $files, fallbackToLatest: ! $request->filled('file'));

        abort_unless($selectedFile, 404);

        return response()->download($selectedFile['path'], $selectedFile['name'], [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    private function logFiles(): Collection
    {
        $directory = storage_path('logs');

        if (! File::isDirectory($directory)) {
            return collect();
        }

        return collect(File::files($directory))
            ->filter(fn ($file) => Str::startsWith($file->getFilename(), 'laravel') && $file->getExtension() === 'log')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values()
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'path' => $file->getPathname(),
                'size' => $file->getSize(),
                'modified_at' => date('Y-m-d H:i:s', $file->getMTime()),
            ]);
    }

    private function selectedFile(Request $request, Collection $files, bool $fallbackToLatest = true): ?array
    {
        if ($files->isEmpty()) {
            return null;
        }

        $requestedName = (string) $request->query('file');

        if ($requestedName !== '') {
            return $files->firstWhere('name', $requestedName);
        }

        return $fallbackToLatest ? $files->first() : null;
    }

    private function parseEntries(string $path): Collection
    {
        $content = $this->readTail($path);
        $entries = [];
        $current = null;

        foreach (preg_split('/\R/', $content) ?: [] as $line) {
            if (preg_match('/^\[(?<date>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(?<env>[^.]+)\.(?<level>[A-Z]+):\s+(?<message>.*)$/', $line, $match)) {
                if ($current) {
                    $entries[] = $this->normalizeEntry($current);
                }

                $current = [
                    'datetime' => $match['date'],
                    'environment' => $match['env'],
                    'level' => $match['level'],
                    'message' => $match['message'],
                    'trace_lines' => [],
                ];

                continue;
            }

            if ($current) {
                $current['trace_lines'][] = $line;
            }
        }

        if ($current) {
            $entries[] = $this->normalizeEntry($current);
        }

        return collect($entries)
            ->reverse()
            ->values()
            ->take(self::ENTRY_LIMIT);
    }

    private function readTail(string $path): string
    {
        if (! is_readable($path)) {
            return '';
        }

        $size = filesize($path) ?: 0;
        $handle = fopen($path, 'rb');

        if (! $handle) {
            return '';
        }

        if ($size > self::READ_BYTES) {
            fseek($handle, -self::READ_BYTES, SEEK_END);
            fgets($handle);
        }

        $content = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $content;
    }

    private function normalizeEntry(array $entry): array
    {
        $trace = trim(implode("\n", $entry['trace_lines']));

        return [
            ...$entry,
            'message' => trim($entry['message']),
            'trace' => $trace,
            'has_trace' => $trace !== '',
            'summary' => Str::limit(trim($entry['message']), 180),
        ];
    }

    private function filterEntries(Collection $entries, string $level, string $search): Collection
    {
        return $entries
            ->when($level !== '', fn (Collection $items) => $items->where('level', $level))
            ->when($search !== '', function (Collection $items) use ($search) {
                $needle = Str::lower($search);

                return $items->filter(function (array $entry) use ($needle) {
                    return Str::contains(Str::lower($entry['message'].' '.$entry['trace']), $needle);
                });
            });
    }
}
