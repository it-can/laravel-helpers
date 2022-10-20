<?php

namespace ITCAN\LaravelHelpers\Extensions;

use Illuminate\Cache\CacheManager as BaseCacheManager;

class CacheManager extends BaseCacheManager
{
    /**
     * Create an instance of the Redis cache driver.
     *
     * @param array $config
     *
     * @return \Illuminate\Cache\Repository
     */
    protected function createRedisDriver(array $config)
    {
        $redis = $this->app['redis'];

        $connection = $config['connection'] ?? 'default';

        $store = new RedisStore($redis, $this->getPrefix($config), $connection);

        return $this->repository(
            $store->setLockConnection($config['lock_connection'] ?? $connection)
        );
    }
}
