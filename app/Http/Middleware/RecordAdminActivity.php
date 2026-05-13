<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordAdminActivity
{
    private const TRACKED_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    private const HIDDEN_INPUTS = [
        '_token',
        '_method',
        'password',
        'password_confirmation',
        'current_password',
        'token',
        'signature_key',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldRecord($request, $response)) {
            $this->record($request, $response);
        }

        return $response;
    }

    private function shouldRecord(Request $request, Response $response): bool
    {
        $user = $request->user();

        return $user
            && $request->is('admin/*')
            && in_array($request->method(), self::TRACKED_METHODS, true)
            && $response->getStatusCode() < 500
            && $user->getRoleNames()->contains(fn (string $role) => $role !== 'user');
    }

    private function record(Request $request, Response $response): void
    {
        try {
            $agent = new Agent();

            ActivityLog::create([
                'user_id' => $request->user()?->id,
                'event' => $this->eventForMethod($request->method()),
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => [
                    'method' => $request->method(),
                    'route' => $request->route()?->getName(),
                    'path' => $request->path(),
                    'action' => $request->route()?->getActionName(),
                    'status_code' => $response->getStatusCode(),
                    'inputs' => $this->safeInputs($request),
                    'route_parameters' => $this->safeRouteParameters($request),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device' => $agent->device(),
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function eventForMethod(string $method): string
    {
        return match ($method) {
            'POST' => 'admin_post',
            'PUT', 'PATCH' => 'admin_update',
            'DELETE' => 'admin_delete',
            default => 'admin_action',
        };
    }

    private function safeInputs(Request $request): array
    {
        return collect($request->except(self::HIDDEN_INPUTS))
            ->map(function ($value) {
                if (is_scalar($value) || $value === null) {
                    return Str::limit((string) $value, 120);
                }

                if (is_array($value)) {
                    return 'array('.count($value).')';
                }

                return get_debug_type($value);
            })
            ->all();
    }

    private function safeRouteParameters(Request $request): array
    {
        return collect($request->route()?->parameters() ?? [])
            ->map(function ($value) {
                if ($value instanceof UrlRoutable) {
                    return $value->getRouteKey();
                }

                if (is_scalar($value) || $value === null) {
                    return $value;
                }

                return class_basename($value);
            })
            ->all();
    }
}
