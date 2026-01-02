<?php

namespace App\Domain\Users;

class Student extends UserRole
{
    public function getRole(): string
    {
        return 'student';
    }

    /**
     * Get permissions for student role
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
            'create_comment' => true,
            'edit_own_comment' => true,
            'delete_own_comment' => true,
            'manage_users' => false,
            'manage_events' => false,
        ];
    }

    /**
     * Check if student has permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->getPermissions()[$permission] ?? false;
    }
}
