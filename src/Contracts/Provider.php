<?php

namespace Fahmiardi\Mongodb\UserProviders\Contracts;

interface Provider
{
    public function users();
    public static function findByName($name);
}