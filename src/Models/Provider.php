<?php

namespace Fahmiardi\Mongodb\UserProviders\Models;

use Moloquent\Eloquent\Model;
use Fahmiardi\Mongodb\UserProviders\Contracts\Provider as ProviderContract;
use Fahmiardi\Mongodb\UserProviders\Exceptions\ProviderDoesNotExist;

class Provider extends Model implements ProviderContract
{
    public function users()
    {
        return $this->getProviders(
            config('auth.model') ?: config('auth.providers.users.model')
        );
    }

    public static function findByName($name)
    {
        $provider = static::where('name', $name)->first();

        if (! $provider) {
            throw new ProviderDoesNotExist();
        }

        return $provider;
    }

    protected function getProviders($model)
    {
        return (new $model)->where('providers.id', $this->getAttribute($this->primaryKey));
    }
}