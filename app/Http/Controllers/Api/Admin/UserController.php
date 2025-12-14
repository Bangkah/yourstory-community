<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends \Illuminate\Routing\Controller
{
    /**
     * List all users with filtering and searching
     */
    public function index(Request $request): JsonResponse
    {
        // Check admin permission
        if ($request->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = min($request->integer('per_page', 15), 100);
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }

        // Sort options
        $sort = $request->string('sort', 'latest');
        match ($sort) {
            'latest' => $query->latest('created_at'),
            'oldest' => $query->oldest('created_at'),
            'name' => $query->orderBy('name'),
            default => $query->latest('created_at'),
        };

        $users = $query->select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Get a specific user
     */
    public function show(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user->load(['stories', 'comments']);
        $user->append(['followers_count', 'following_count']);

        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Update user role
     */
    public function updateRole(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent admin from changing their own role
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Cannot change your own role'], 400);
        }

        $validated = $request->validate([
            'role' => ['required', 'in:' . implode(',', [User::ROLE_ADMIN, User::ROLE_MODERATOR, User::ROLE_MEMBER])],
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        return response()->json([
            'message' => 'User role updated successfully',
            'data' => [
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $user->role,
            ],
        ]);
    }

    /**
     * Suspend/unsuspend user
     */
    public function toggleSuspend(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent admin from suspending themselves
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Cannot suspend your own account'], 400);
        }

        $user->update(['is_suspended' => !$user->is_suspended]);

        return response()->json([
            'message' => $user->is_suspended ? 'User suspended' : 'User unsuspended',
            'data' => [
                'user_id' => $user->id,
                'is_suspended' => $user->is_suspended,
            ],
        ]);
    }

    /**
     * Delete user
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent admin from deleting themselves
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Cannot delete your own account'], 400);
        }

        $userName = $user->name;
        $user->delete();

        return response()->json([
            'message' => "User '{$userName}' has been deleted",
            'data' => [
                'deleted_user_id' => $user->id,
            ],
        ]);
    }
}
