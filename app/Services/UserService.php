<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Valid roles in the system
     *
     * @var array
     */
    protected array $validRoles = ['admin', 'bendahara', 'staff'];

    /**
     * Get all users with pagination
     *
     * @param int $perPage Number of users per page
     * @return LengthAwarePaginator
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::orderBy('name')->paginate($perPage);
    }

    /**
     * Create new user
     *
     * @param array $data User data containing name, email, password, role
     * @return User
     */
    public function createUser(array $data): User
    {
        // Hash password before creating user
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Update user
     *
     * @param User $user User instance to update
     * @param array $data Updated data (name, email, role)
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        
        return $user->fresh();
    }

    /**
     * Delete user
     *
     * @param User $user User instance to delete
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Reset user password
     *
     * @param User $user User instance
     * @param string $newPassword New password (plain text)
     * @return bool
     */
    public function resetPassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Validate if role is valid
     *
     * @param string $role Role to validate
     * @return bool
     */
    public function isValidRole(string $role): bool
    {
        return in_array($role, $this->validRoles);
    }
}
