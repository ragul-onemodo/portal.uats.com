<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateDevice
{

    const MAX_DRIFT = 300;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        $deviceId = $request->header('X-Device-Id');
        $timestamp = $request->header('X-Timestamp');
        $signature = $request->header('X-Signature');

        if (!$deviceId || !$timestamp || !$signature) {
            return response()->json(['error' => 'Missing auth headers'], 401);
        }

        // Replay protection
        if (abs(time() - (int) $timestamp) > self::MAX_DRIFT) {
            return response()->json(['error' => 'Request expired'], 401);
        }

        $device = Device::where('device_uid', $deviceId)
            ->where('is_active', true)
            ->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid device'], 401);
        }

        // MESSAGE = device_id@timestamp
        $message = $deviceId . '@' . $timestamp;

        $expectedSignature = 'v1=' . hash_hmac(
            'sha256',
            $message,
            $device->api_key
        );


        // return response()->json(['error' => $expectedSignature, 'hash-req' => $signature, 'api-secret' => $device->api_key], 401);


        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Attach device context
        $request->attributes->set('device', $device);

        return $next($request);
    }
}
