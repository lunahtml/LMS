<?php
// backend/app/Http/Middleware/CheckUserRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Проверяем роль
        if (!$user->hasRole($role)) {
            abort(403, 'У вас нет доступа к этой панели.');
        }

        return $next($request);
    }
}