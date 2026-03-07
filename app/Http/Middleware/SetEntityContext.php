<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetEntityContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !session()->has('entity_id')) {

            $entityId = \DB::table('entity_users')
                ->where('user_id', auth()->id())
                ->value('entity_id');

            session([
                'entity_id' => $entityId,
            ]);
        }

        return $next($request);
    }
}
