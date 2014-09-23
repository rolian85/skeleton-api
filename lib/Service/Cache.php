<?php
namespace Service;

class Cache extends \Phalcon\DI\Injectable
{
    private $_driver;
    private $_methods;

    public function __construct()
    {
        $cache = $this->ConfigService->cache;
        $driver = null;

        if($cache->enabled) {
            switch($cache->adapter) {
                case 'redis':
                    $redis = new \Redis();
                    $redis->connect($cache->config->host, $cache->config->port);

                    $driver = new \Doctrine\Common\Cache\RedisCache();
                    $driver->setRedis($redis);
                    $driver->setNamespace($cache->config->namespace);
                    break;
            }
        }

        $this->_driver = $driver;
        $this->_methods = array(
            'fetch',
            'contains',
            'save',
            'delete',
            'flushAll',
            'deleteAll'
        );
    }

    public function getDriver()
    {
        return $this->_driver;
    }

    public function isEnabled()
    {
        return is_object($this->getDriver());
    }

    public function __call($name, $arguments)
    {
        $driver = $this->getDriver();
        if(!is_object($driver)) {
            throw new \Exception($this->MessageService->get('cacheServiceCacheDriverNotSetup'));
        }

        if(!in_array($name, $this->_methods) || !method_exists($driver, $name)) {
            throw new \Exception($this->MessageService->get('cacheServiceMethodNotAllowed'));
        }

        return call_user_func_array(array($driver, $name), $arguments);
    }
}