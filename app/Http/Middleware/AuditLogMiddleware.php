<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Log all state-changing HTTP operations (POST, PUT, PATCH, DELETE) for OWASP compliance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya catat tindakan mutasi data yang berhasil
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && $response->isSuccessful()) {
            $user = $request->user();

            // Masking data sensitif
            $payload = $request->except([
                'password',
                'password_confirmation',
                'two_factor_secret',
                'token',
                '_token'
            ]);

            AuditLog::create([
                'user_id' => $user?->id,
                'action' => $request->method(),
                'module' => $request->segment(1) ?? 'system',
                'description' => "Pengguna {$user?->name} ({$user?->role}) mengakses {$request->path()}",
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'payload' => !empty($payload) ? json_encode($payload) : null,
            ]);
        }

        return $response;
    }
}
