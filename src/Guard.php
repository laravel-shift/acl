<?php

declare(strict_types=1);

namespace Konekt\Acl;

use Illuminate\Support\Collection;

class Guard
{
    /**
     * return collection of (guard_name) property if exist on class or object
     * otherwise will return collection of guards names that exists in config/auth.php.
     * @param $model
     * @return Collection
     */
    public static function getNames($model): Collection
    {
        if (is_object($model)) {
            $guardName = $model->guard_name ?? null;
        }

        if (! isset($guardName)) {
            $class = is_object($model) ? get_class($model) : $model;

            $guardName = (new \ReflectionClass($class))->getDefaultProperties()['guard_name'] ?? null;
        }

        if ($guardName) {
            return collect($guardName);
        }

        return collect(config('auth.guards'))
            ->map(function ($guard) {
                if (! isset($guard['provider'])) {
                    return;
                }

                return config("auth.providers.{$guard['provider']}.model");
            })
            ->filter(function ($model) use ($class) {
                return $class === $model;
            })
            ->keys();
    }

    public static function getDefaultName($class): string
    {
        $default = config('auth.defaults.guard');

        return static::getNames($class)->first() ?: $default;
    }
}
