<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedisCache
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $key = 'redis_cache:' . md5($request->fullUrl());

        $start = microtime(true);

        if (Cache::has($key)) {

            $cached = Cache::get($key);
            $headers = array_merge(
                $cached['headers'],
                ['x-cache' => 'HIT', 'x-response-time'=> round((microtime(true) - $start) * 1000) . 'ms']
            );

            return response()->json($cached['body'], 200, $headers);
        }

        $response = $next($request);

        $data = [
            'headers' => $response->headers->all(),
            'body' => $response->getOriginalContent(),
        ];

        Cache::put($key, $data, now()->addMinutes(60));

        $response->headers->set('x-cache', 'MISS');
        $response->headers->set('x-response-time', round((microtime(true) - $start) * 1000) . 'ms');

        return $response;
    }
}
