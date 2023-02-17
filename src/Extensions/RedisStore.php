<?php

namespace ITCAN\LaravelHelpers\Extensions;

use Illuminate\Cache\RedisStore as BaseRedisStore;

class RedisStore extends BaseRedisStore
{
    protected function serialize($value)
    {
        return $value;
    }

    protected function unserialize($value)
    {
        return $value;
    }
}
