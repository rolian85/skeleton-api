<?php
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Doctrine implements MiddlewareInterface
{
    public function call($app)
    {
        $configService = $app->getService('ConfigService');
        $defaultDoctrineConfig = $configService->doctrine->default;

        $mappingConfig = $defaultDoctrineConfig->mapping;
        $connectionConfig = $defaultDoctrineConfig->connection;

        foreach($defaultDoctrineConfig->autoload->annotations as $namespace => $path) {
            AnnotationRegistry::registerAutoloadNamespace($namespace, $path);
        }

        $cacheDriver = ($mappingConfig->cache) ? $app->getService('CacheService')->getDriver() : null;
        if(is_object($cacheDriver)) {
            $cacheDriver = clone $cacheDriver;
        }
        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($mappingConfig->entityPaths->toArray(),
                                                                       !$mappingConfig->cache,
                                                                       $mappingConfig->proxyDir,
                                                                       $cacheDriver,
                                                                       false);

        $doctrineConfig->addFilter('soft-deleteable', '\Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter');
        $em = EntityManager::create($connectionConfig->toArray(), $doctrineConfig);
        $em->getFilters()->enable('soft-deleteable');

        foreach($defaultDoctrineConfig->subscribers as $subscriberClass) {
            $em->getEventManager()->addEventSubscriber(new $subscriberClass());
        }

        $app->setService('em', $em);
    }
}