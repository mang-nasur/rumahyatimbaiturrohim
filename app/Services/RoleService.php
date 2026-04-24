<?php

namespace App\Services;

use App\Models\User;

class RoleService
{
    /**
     * Role permissions matrix based on design document
     * 
     * @var array
     */
    protected array $permissionsMatrix = [
        'admin' => [
            'anak-yatim' => ['create', 'read', 'update', 'delete'],
            'transaksi' => ['create', 'read', 'update', 'delete'],
            'laporan' => ['read', 'export'],
            'user-management' => ['create', 'read', 'update', 'delete'],
            'dashboard' => ['read'],
        ],
        'bendahara' => [
            'anak-yatim' => ['read'],
            'transaksi' => ['create', 'read', 'update', 'delete'],
            'laporan' => ['read', 'export'],
            'dashboard' => ['read'],
        ],
        'staff' => [
            'anak-yatim' => ['create', 'read', 'update', 'delete'],
            'laporan' => ['read', 'export'],
            'dashboard' => ['read'],
        ],
    ];

    /**
     * Get available roles in the system
     *
     * @return array
     */
    public function getAvailableRoles(): array
    {
        return ['admin', 'bendahara', 'staff'];
    }

    /**
     * Check if user can access a specific feature
     *
     * @param User $user
     * @param string $feature Feature name (e.g., 'anak-yatim', 'transaksi')
     * @return bool
     */
    public function canAccessFeature(User $user, string $feature): bool
    {
        // Admin has universal access
        if ($user->isAdmin()) {
            return true;
        }

        $role = $user->role;
        
        // Check if role exists and has access to the feature
        return isset($this->permissionsMatrix[$role][$feature]);
    }

    /**
     * Get all permissions for a specific role
     *
     * @param string $role
     * @return array
     */
    public function getRolePermissions(string $role): array
    {
        return $this->permissionsMatrix[$role] ?? [];
    }

    /**
     * Check if a role can perform a specific action on a feature
     *
     * @param string $role
     * @param string $feature Feature name (e.g., 'anak-yatim', 'transaksi')
     * @param string $action Action name (e.g., 'create', 'read', 'update', 'delete')
     * @return bool
     */
    public function canPerformAction(string $role, string $feature, string $action): bool
    {
        // Admin has universal access
        if ($role === 'admin') {
            return true;
        }

        // Check if role has permission for the feature and action
        if (!isset($this->permissionsMatrix[$role][$feature])) {
            return false;
        }

        return in_array($action, $this->permissionsMatrix[$role][$feature]);
    }
}
