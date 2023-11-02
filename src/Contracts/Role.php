<?php

declare(strict_types=1);

namespace Konekt\Acl\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    public function permissions(): BelongsToMany;

    public static function findByName(string $name, ?string $guardName = null): ?self ;

    public static function findById(int $id, ?string $guardName = null): ?self;

    public function hasPermissionTo(string|Permission $permission): bool;
}
