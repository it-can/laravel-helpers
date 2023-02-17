<?php

namespace ITCAN\LaravelHelpers\Extensions;

use Illuminate\Cache\CacheManager as BaseCacheManager;

class CacheManager extends BaseCacheManager
{
    /**
     * Create an instance of the Redis cache driver.
     *
     * @param  array  $config
     * @return \Illuminate\Cache\Repository
     */
    protected function createRedisDriver(array $config)
    {
        $redisConfig = config('database.redis', []);
        $connection = $config['connection'] ?? 'default';

        // Load custom RedisStore when using phpredis (this will take care of serialization by the extension)
        if (isset($redisConfig['client'])
            && $redisConfig['client'] === 'phpredis'
            && (isset($redisConfig['options']['serializer']) || isset($redisConfig[$connection]['options']['serializer']))
        ) {
            $redis = $this->app['redis'];

            $store = new RedisStore($redis, $this->getPrefix($config), $connection);

            return $this->repository(
                $store->setLockConnection($config['lock_connection'] ?? $connection)
            );
        }

        return parent::createRedisDriver($config);
    }
}
