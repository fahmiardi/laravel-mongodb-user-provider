<?php

namespace Fahmiardi\Mongodb\UserProviders\Traits;

use Fahmiardi\Mongodb\UserProviders\Contracts\Provider;
use Fahmiardi\Mongodb\UserProviders\Contracts\EmbedProvider;
use Carbon\Carbon;

trait HasProviders
{
    public function providers()
    {
        return $this->embedsMany(app(EmbedProvider::class));
    }

    public function addProvider($provider, $uniqueId, $meta = [])
    {
        $provider = $this->getStoredProvider($provider);

        if (! $this->providers()->where([
            'id' => $provider->_id,
            'unique' => $uniqueId
        ])->first()) {
            $this->providers()->associate(app(EmbedProvider::class)->forceFill([
                'id' => $provider->_id,
                'unique' => $uniqueId,
                'meta' => $meta,
                'created_at' => $now = Carbon::now(),
                'updated_at' => $now
            ]));

            $this->save();
        }

        return $this;
    }

    public function getProvider($provider)
    {
        $provider = $this->getStoredProvider($provider);

        return $this->providers()->where('id', $provider->_id)->first();
    }

    public function removeProvider($provider)
    {
        $provider = $this->getStoredProvider($provider);
        $embedProvider = $this->providers()->where('id', $provider->_id);

        $this->providers()->detach($embedProvider);

        return $this;
    }

    public function hasProvider($providers)
    {
        if (is_string($providers)) {
            $providers = $this->getStoredProvider($providers);

            return $this->providers->contains('id', $providers->_id);
        }

        if ($providers instanceof Provider) {
            return $this->providers->contains('id', $providers->_id);
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