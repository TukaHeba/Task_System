<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Retrieve all users with pagination.
     * 
     * Fetch paginated users
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getAllUsers()
    {
        try {
            return User::paginate(10);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve users: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Create a new user with the provided data.
     * 
     * @param array $data The validated data to create a user.
     * @return User|null The created user object on success, or null on failure.
     * @throws \Exception
     */
    public function createUser(array $data): ?User
    {
        try {
            $user = User::create($data);
            $user->role = $data['role'];
            $user->save();

            return $user;
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Retrieve a single user.
     * 
     * @param User $user The user object.
     * @return User|null The retrieved user, or null on failure.
     * @throws \Exception
     */
    public function showUser(User $user): ?User
    {
        try {
            return $user;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update an existing user with the provided data.
     * 
     * @param User $user The user to update.
     * @param array $data The validated data to update the user.
     * @return User|null The updated user object on success, or null on failure.
     * @throws \Exception
     */
    public function updateUser(User $user, array $data): ?User
    {
        try {
            $user->update($data);

            if (isset($data['role'])) {
                $user->role = $data['role'];
                $user->save();
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Delete a user.
     * 
     * @param User $user The user to delete.
     * @return bool True on success, false on failure.
     * @throws \Exception
     */
    public function deleteUser(User $user): bool
    {
        try {
            return $user->delete();
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }
}
