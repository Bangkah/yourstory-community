<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Firebase\FirebaseAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FirebaseAuthMiddleware
{
    public function __construct(private readonly FirebaseAuthService $firebaseAuth)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $this->extractToken($request);

        if (!$token) {
            throw new UnauthorizedHttpException('Bearer', 'Firebase ID token is missing.');
        }

        try {
            $firebaseUser = $this->firebaseAuth->verifyIdToken($token);
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Bearer', $e->getMessage());
        }

        if (!$firebaseUser['uid']) {
            throw new UnauthorizedHttpException('Bearer', 'Firebase UID is missing.');
        }

        $user = User::firstOrCreate(
            ['firebase_uid' => $firebaseUser['uid']],
            [
                'email' => $firebaseUser['email'] ?? null,
                'name' => $firebaseUser['name'] ?? 'User',
                'role' => User::ROLE_MEMBER,
            ]
        );

        if (!$user->email && $firebaseUser['email'] ?? false) {
            $user->forceFill(['email' => $firebaseUser['email']])->save();
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        if ($request->bearerToken()) {
            return $request->bearerToken();
        }

        return $request->header('X-Firebase-Auth');
    }
}
