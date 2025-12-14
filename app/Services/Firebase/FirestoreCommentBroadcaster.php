<?php

namespace App\Services\Firebase;

use App\Models\Comment;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
use Google\Cloud\Firestore\FirestoreClient;
use Google\ApiCore\ApiException;

class FirestoreCommentBroadcaster
{
    private $firestore = null;

    public function __construct($firestore = null)
    {
        $this->firestore = $firestore;
    }

    public function broadcast(Comment $comment): void
    {
        $client = $this->firestore ?? $this->makeClient();

        if ($client) {
            try {
                $client
                    ->collection('stories/'.$comment->story_id.'/comments')
                    ->document((string) $comment->id)
                    ->set([
                        'id' => $comment->id,
                        'story_id' => $comment->story_id,
                        'user_id' => $comment->user_id,
                        'body' => $comment->body,
                        'depth' => $comment->depth,
                        'created_at' => $comment->created_at,
                    ]);

                return;
            } catch (\Throwable $e) {
                Log::warning('Failed to broadcast comment to Firestore (client)', [
                    'comment_id' => $comment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->broadcastViaRest($comment);
    }

    private function makeClient(): ?object
    {
        $clientClass = '\\Google\\Cloud\\Firestore\\FirestoreClient';

        if (!class_exists($clientClass)) {
            return null;
        }

        $projectId = env('FIREBASE_PROJECT_ID');

        if (!$projectId) {
            return null;
        }

        $config = ['projectId' => $projectId];

        if ($credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS')) {
            $config['keyFilePath'] = $credentialsPath;
        }

        return new $clientClass($config);
    }

    private function broadcastViaRest(Comment $comment): void
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');

        if (!$projectId || !$credentialsPath || !class_exists(ServiceAccountCredentials::class)) {
            return;
        }

        try {
            $creds = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/datastore'],
                $credentialsPath
            );

            $tokenInfo = $creds->fetchAuthToken();
            $accessToken = $tokenInfo['access_token'] ?? null;

            if (!$accessToken) {
                return;
            }

            $documentUrl = sprintf(
                'https://firestore.googleapis.com/v1/projects/%s/databases/(default)/documents/stories/%s/comments?documentId=%s',
                $projectId,
                $comment->story_id,
                $comment->id
            );

            $payload = [
                'fields' => [
                    'id' => ['integerValue' => (string) $comment->id],
                    'story_id' => ['integerValue' => (string) $comment->story_id],
                    'user_id' => ['integerValue' => (string) $comment->user_id],
                    'body' => ['stringValue' => $comment->body],
                    'depth' => ['integerValue' => (string) $comment->depth],
                    'created_at' => ['timestampValue' => $comment->created_at?->toIso8601String()],
                ],
            ];

            Http::withToken($accessToken)
                ->acceptJson()
                ->post($documentUrl, $payload);
        } catch (\Throwable $e) {
            Log::warning('Failed to broadcast comment to Firestore (REST)', [
                'comment_id' => $comment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
