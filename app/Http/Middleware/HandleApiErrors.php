<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HandleApiErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('API Error: ' . $e->getMessage(), [
                'exception' => $e,
                'url' => $request->fullUrl(),
            ]);

            // Return generic error in production
            $message = config('app.debug') ? $e->getMessage() : 'Internal server error';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        }
    }
}
