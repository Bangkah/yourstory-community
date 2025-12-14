<?php

namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FirebaseAuthService
{
    public function __construct(private readonly ?string $apiKey = null)
    {
    }

    public function verifyIdToken(string $idToken): array
    {
        $apiKey = $this->apiKey ?? env('FIREBASE_WEB_API_KEY');

        if (!$apiKey) {
            throw new RuntimeException('Firebase API key is not configured.');
        }

        $response = Http::asJson()
            ->post('https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.$apiKey, [
                'idToken' => $idToken,
            ]);

        if (!$response->ok() || empty($response['users'][0])) {
            throw new RuntimeException('Invalid Firebase ID token.');
        }

        $firebaseUser = $response['users'][0];

        return [
            'uid' => $firebaseUser['localId'] ?? null,
            'email' => $firebaseUser['email'] ?? null,
            'name' => $firebaseUser['displayName'] ?? null,
            'picture' => $firebaseUser['photoUrl'] ?? null,
        ];
    }
}
