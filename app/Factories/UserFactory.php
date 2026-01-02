<?php

namespace App\Factories;

use App\Domain\Users\Admin;
use App\Domain\Users\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    public static function create(array $data)
    {
        // Create appropriate role object based on role
        $role = self::createRole($data['role'] ?? 'student');

        // Prepare data for persistence
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role->getRole(),
            'status' => $data['status'] ?? 'active'
        ];

        // Persist to database via User Model
        $user = User::create($userData);

        // Assign domain role to user for behavior/permission logic
        $user->setDomainRole($role);

        return $user;
    }

    public static function createRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return new Admin();
            case 'student':
                return new Student();
            default:
                throw new \InvalidArgumentException("Invalid user role: {$role}");
        }
    }

    public static function createAdmin(array $data)
    {
        $data['role'] = 'admin';
        return self::create($data);
    }
}
