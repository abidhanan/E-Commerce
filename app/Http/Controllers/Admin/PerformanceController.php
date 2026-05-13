<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PerformanceController extends Controller
{
    private const DEFAULT_DAYS = 30;
    private const ALLOWED_DAYS = [7, 30, 90, 180];

    private const EVENT_LABELS = [
        'login' => 'Login',
        'created' => 'Create Data',
        'published' => 'Publish Konten',
        'admin_post' => 'Submit Admin',
        'admin_update' => 'Update Admin',
        'admin_delete' => 'Delete Admin',
    ];

    public function index(Request $request): View
    {
        [$from, $to, $days] = $this->dateRange($request);
        $selectedRole = $request->query('role');

        $roles = Role::query()
            ->where('name', '!=', 'user')
            ->orderBy('name')
            ->get();

        $staffUsers = User::query()
            ->with('roles')
            ->whereHas('roles', fn (Builder $query) => $query->where('name', '!=', 'user'))
            ->when($selectedRole, fn (Builder $query) => $query->whereHas('roles', fn (Builder $roleQuery) => $roleQuery->where('name', $selectedRole)))
            ->orderBy('name')
            ->get();

        $logs = ActivityLog::query()
            ->with('user.roles')
            ->whereIn('user_id', $staffUsers->pluck('id'))
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $staffPerformance = $this->staffPerformance($staffUsers, $logs);
        $roleSummary = $this->roleSummary($roles, $staffPerformance);
        $eventSummary = $this->eventSummary($logs);

        return view('Admin.performance.index', [
            'roles' => $roles,
            'selectedRole' => $selectedRole,
            'from' => $from,
            'to' => $to,
            'days' => $days,
            'summary' => [
                'staff_count' => $staffPerformance->count(),
                'active_staff_count' => $staffPerformance->where('activity_count', '>', 0)->count(),
                'activity_count' => $logs->count(),
                'contribution_count' => $logs->reject(fn (ActivityLog $log) => $log->event === 'login')->count(),
                'average_score' => round($staffPerformance->avg('score') ?? 0, 1),
            ],
            'roleSummary' => $roleSummary,
            'staffPerformance' => $staffPerformance,
            'eventSummary' => $eventSummary,
            'recentActivities' => $logs->take(30),
            'eventLabels' => self::EVENT_LABELS,
        ]);
    }

    public function show(Request $request, User $staff): View
    {
        abort_unless($this->isStaff($staff), 404);

        [$from, $to, $days] = $this->dateRange($request);

        $staff->load('roles');

        $logs = ActivityLog::query()
            ->where('user_id', $staff->id)
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $row = $this->staffPerformance(collect([$staff]), $logs)->first();
        $dailyActivity = $this->dailyActivity($logs, $from, $to);

        return view('Admin.performance.show', [
            'staff' => $staff,
            'row' => $row,
            'from' => $from,
            'to' => $to,
            'days' => $days,
            'logs' => $logs,
            'dailyActivity' => $dailyActivity,
            'eventSummary' => $this->eventSummary($logs),
            'routeSummary' => $this->routeSummary($logs),
            'deviceSummary' => $this->deviceSummary($logs),
            'eventLabels' => self::EVENT_LABELS,
        ]);
    }

    private function staffPerformance($staffUsers, $logs)
    {
        $logsByUser = $logs->groupBy('user_id');

        return $staffUsers
            ->map(function (User $staff) use ($logsByUser) {
                $userLogs = $logsByUser->get($staff->id, collect());
                $contributionLogs = $userLogs->reject(fn (ActivityLog $log) => $log->event === 'login');
                $loginCount = $userLogs->where('event', 'login')->count();
                $activeDays = $userLogs
                    ->pluck('created_at')
                    ->filter()
                    ->map(fn (Carbon $date) => $date->format('Y-m-d'))
                    ->unique()
                    ->count();

                $roles = $staff->roles
                    ->pluck('name')
                    ->reject(fn (string $role) => $role === 'user')
                    ->values();

                return [
                    'user' => $staff,
                    'roles' => $roles,
                    'activity_count' => $userLogs->count(),
                    'contribution_count' => $contributionLogs->count(),
                    'login_count' => $loginCount,
                    'active_days' => $activeDays,
                    'score' => $this->score($contributionLogs->count(), $loginCount, $activeDays),
                    'last_activity_at' => $userLogs->max('created_at'),
                    'last_login_at' => $userLogs->where('event', 'login')->max('created_at'),
                    'top_events' => $contributionLogs
                        ->groupBy('event')
                        ->map(fn ($eventLogs, string $event) => [
                            'event' => $event,
                            'label' => self::EVENT_LABELS[$event] ?? str_replace('_', ' ', $event),
                            'count' => $eventLogs->count(),
                        ])
                        ->sortByDesc('count')
                        ->values()
                        ->take(3),
                ];
            })
            ->sortByDesc('score')
            ->values();
    }

    private function roleSummary($roles, $staffPerformance)
    {
        return $roles
            ->map(function (Role $role) use ($staffPerformance) {
                $members = $staffPerformance->filter(fn (array $row) => $row['roles']->contains($role->name));

                return [
                    'role' => $role,
                    'member_count' => $members->count(),
                    'active_member_count' => $members->where('activity_count', '>', 0)->count(),
                    'activity_count' => $members->sum('activity_count'),
                    'contribution_count' => $members->sum('contribution_count'),
                    'login_count' => $members->sum('login_count'),
                    'average_score' => round($members->avg('score') ?? 0, 1),
                    'last_activity_at' => $members->max('last_activity_at'),
                ];
            })
            ->filter(fn (array $row) => $row['member_count'] > 0)
            ->values();
    }

    private function eventSummary($logs)
    {
        return $logs
            ->groupBy('event')
            ->map(fn ($eventLogs, string $event) => [
                'event' => $event,
                'label' => self::EVENT_LABELS[$event] ?? str_replace('_', ' ', $event),
                'count' => $eventLogs->count(),
                'last_at' => $eventLogs->max('created_at'),
            ])
            ->sortByDesc('count')
            ->values();
    }

    private function routeSummary($logs)
    {
        return $logs
            ->reject(fn (ActivityLog $log) => $log->event === 'login')
            ->groupBy(fn (ActivityLog $log) => data_get($log->new_values, 'route') ?: 'manual-log')
            ->map(fn ($routeLogs, string $route) => [
                'route' => $route,
                'count' => $routeLogs->count(),
                'last_at' => $routeLogs->max('created_at'),
            ])
            ->sortByDesc('count')
            ->values()
            ->take(12);
    }

    private function deviceSummary($logs)
    {
        return $logs
            ->groupBy(fn (ActivityLog $log) => trim(($log->device ?: 'Unknown').' / '.($log->browser ?: 'Unknown')))
            ->map(fn ($deviceLogs, string $device) => [
                'device' => $device,
                'count' => $deviceLogs->count(),
            ])
            ->sortByDesc('count')
            ->values();
    }

    private function dailyActivity($logs, Carbon $from, Carbon $to)
    {
        $logsByDate = $logs->groupBy(fn (ActivityLog $log) => $log->created_at?->format('Y-m-d'));

        return collect(CarbonPeriod::create($from->copy()->startOfDay(), '1 day', $to->copy()->startOfDay()))
            ->map(function (Carbon $date) use ($logsByDate) {
                $dateLogs = $logsByDate->get($date->format('Y-m-d'), collect());

                return [
                    'date' => $date,
                    'login_count' => $dateLogs->where('event', 'login')->count(),
                    'contribution_count' => $dateLogs->reject(fn (ActivityLog $log) => $log->event === 'login')->count(),
                    'activity_count' => $dateLogs->count(),
                ];
            })
            ->sortByDesc(fn (array $row) => $row['date']->timestamp)
            ->values();
    }

    private function dateRange(Request $request): array
    {
        $days = (int) $request->query('days', self::DEFAULT_DAYS);
        $days = in_array($days, self::ALLOWED_DAYS, true) ? $days : self::DEFAULT_DAYS;

        $from = $request->filled('date_from')
            ? Carbon::parse($request->date('date_from'))->startOfDay()
            : now()->subDays($days - 1)->startOfDay();

        $to = $request->filled('date_to')
            ? Carbon::parse($request->date('date_to'))->endOfDay()
            : now()->endOfDay();

        if ($from->gt($to)) {
            return [$to->copy()->startOfDay(), $from->copy()->endOfDay(), $days];
        }

        return [$from, $to, $days];
    }

    private function isStaff(User $staff): bool
    {
        return $staff->roles()
            ->where('name', '!=', 'user')
            ->exists();
    }

    private function score(int $contributionCount, int $loginCount, int $activeDays): int
    {
        return min(100, ($contributionCount * 6) + ($activeDays * 3) + min($loginCount, 20));
    }
}