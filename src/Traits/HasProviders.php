<?php

namespace Fahmiardi\Mongodb\UserProviders\Traits;

use Fahmiardi\Mongodb\UserProviders\Contracts\Provider;
use Fahmiardi\Mongodb\UserProviders\Contracts\UserProvider;
use Fahmiardi\Mongodb\UserProviders\Exceptions\ProviderOwnedByOther;

trait HasProviders
{
    public function providers()
    {
        return $this->hasMany(app(UserProvider::class), 'user_id');
    }

    public function addProvider($provider, $uniqueId, $meta = [])
    {
        $provider = $this->getStoredProvider($provider);
        $existProvider = app(UserProvider::class)->where([
            'provider_id' => $provider->_id,
            'provider_unique' => $uniqueId
        ])->first();

        if (
            $existProvider &&
            $existProvider->user_id !== $this->getAttribute($this->primaryKey)
        ) {
            throw new ProviderOwnedByOther();
        }

        if (! $existProvider) {
            $this->providers()->save(app(UserProvider::class)->forceFill([
                'provider_id' => $provider->_id,
                'provider_unique' => $uniqueId,
                'meta' => $meta
            ]));
        }

        return $this;
    }

    public function getProvider($provider)
    {
        $provider = $this->getStoredProvider($provider);

        return $this->providers()->where('provider_id', $provider->_id)->first();
    }

    public function removeProvider($provider)
    {
        $provider = $this->getStoredProvider($provider);

        $this->providers()->where('provider_id', $provider->_id)->delete();

        return $this;
    }

    public function hasProvider($providers)
    {
        if (is_string($providers)) {
            $providers = $this->getStoredProvider($providers);

            return $this->providers->contains('provider_id', $providers->_id);
        }

        if ($providers instanceof Provider) {
            return $this->providers->contains('provider_id', $providers->_id);
        }

        if (is_array($providers)) {
            foreach ($providers as $provider) {
                if ($this->hasProvider($provider)) {
                    return true;
                }
            }

            return false;
        }

        return (bool) $providers->intersect($this->providers)->count();
    }

    protected function getStoredProvider($provider)
    {
        if (is_string($provider)) {
            return app(Provider::class)->findByName($provider);
        }

        return $provider;
    }
}