<?php

namespace Fahmiardi\Mongodb\UserProviders\Contracts;

interface UserProvider
{
    public function provider();
    public function user();
}