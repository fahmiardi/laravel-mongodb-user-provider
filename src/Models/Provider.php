<?php

namespace Fahmiardi\Mongodb\UserProviders\Models;

use Moloquent\Eloquent\Model;
use Fahmiardi\Mongodb\UserProviders\Contracts\Provider as ProviderContract;
use Fahmiardi\Mongodb\UserProviders\Exceptions\ProviderDoesNotExist;

class Provider extends Model implements ProviderContract
{
    public function users()
    {
        return $this->hasMany(app(UserProvider::class), 'provider_id');
    }

    public static function findByName($name)
    {
        $provider = static::where('name', $name)->first();

        if (! $provider) {
            throw new ProviderDoesNotExist();
        }

        return $provider;
    }
}