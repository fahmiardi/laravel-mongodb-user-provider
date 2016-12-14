<?php

namespace Fahmiardi\Mongodb\UserProviders\Models;

use Moloquent\Eloquent\Model;
use Fahmiardi\Mongodb\UserProviders\Contracts\UserProvider as UserProviderContract;

class UserProvider extends Model implements UserProviderContract
{
    public function provider()
    {
        return $this->belongsTo(app(Provider::class));
    }

    public function user()
    {
        return $this->belongsTo(
            config('auth.model') ?: config('auth.providers.users.model')
        );
    }
}