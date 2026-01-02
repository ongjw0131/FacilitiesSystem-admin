<?php

namespace App\Domain\Users;

class Admin extends UserRole
{
    public function getRole(): string
    {
        return 'admin';
    }

    /**
     * Get permissions for admin role
     */
    public function getPermissions(): array
    {
        return [
            'view_events' => true,
            'view_societies' => true,
            'join_society' => true,
            'create_post' => true,
            'edit_own_post' => true,
            'delete_own_post' => true,
            'edit_any_post' => true,
            'delete_any_post' => true,
            'create_comment' => true,
            'edit_own_comment' => true,
            'delete_own_comment' => true,
            'edit_any_comment' => true,
            'delete_any_comment' => true,
            'manage_users' => true,
            'manage_events' => true,
            'manage_societies' => true,
        ];
    }

    /**
     * Check if admin has permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->getPermissions()[$permission] ?? false;
    }
}
