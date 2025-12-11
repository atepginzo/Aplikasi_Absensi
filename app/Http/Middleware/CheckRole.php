<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => explode(',', $role))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->unique()
            ->toArray();

        if (empty($allowedRoles) || in_array($user->role, $allowedRoles, true)) {
            return $next($request);
        }

        return $this->redirectAccordingToRole($user->role);
    }

    protected function redirectAccordingToRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('dashboard')->with('status', 'Anda tidak memiliki akses ke halaman tersebut.'),
            'wali_kelas' => redirect()->route('wali.dashboard')->with('status', 'Anda tidak memiliki akses ke halaman tersebut.'),
            'orang_tua' => redirect()->route('orang-tua.dashboard')->with('status', 'Anda tidak memiliki akses ke halaman tersebut.'),
            default => redirect()->route('login'),
        };
    }
}
