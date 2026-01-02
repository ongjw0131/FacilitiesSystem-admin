<?php

namespace App\Domain\Users;

abstract class UserRole
{
    abstract public function getRole(): string;

    abstract public function getPermissions(): array;

    abstract public function hasPermission(string $permission): bool;
}
