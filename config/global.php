<?php
/**
 * Created by PhpStorm.
 * User: rolian85
 * Date: 9/18/14
 * Time: 11:29 PM
 */

$crudActions = array(
    'get' => array(
        'route' => '/{id:[a-zA-Z0-9\-]+}',
        'method' => 'get'
    ),
    'create' => array(
        'route' => '',
        'method' => 'post'
    ),
    'update' => array(
        'route' => '/{id:[a-zA-Z0-9\-]+}',
        'method' => 'put'
    ),
    'delete' => array(
        'route' => '/{id:[a-zA-Z0-9\-]+}',
        'method' => 'delete'
    )
);

return array(
    'autoload' => array(
        __DIR__ . '/../module/',
        __DIR__ . '/../model/',
        __DIR__ . '/../lib/',
    ),
    'phpSettings' => array(
        'error_reporting' => 0,
        'date.timezone' => 'UTC'
    ),
    'doctrine' => array(
        'default' => array(
            'mapping' => array(
                'cache' => true,
                'entityPaths' => array(
                    __DIR__ . '/../model/Entity'
                ),
                'proxyDir' => __DIR__ . '/../data/Proxy',
            ),
            'connection' => array(
                'host'     => '127.0.0.1',
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'default_db',
            ),
            'subscribers' => array(
                'Gedmo\Timestampable\TimestampableListener',
                'Gedmo\SoftDeleteable\SoftDeleteableListener',
                'Doctrine\Custom\Event\Listener'
            ),
            'autoload' => array(
                'annotations' => array(
                    'Gedmo\Mapping\Annotation' => __DIR__ . '/../vendor/gedmo/doctrine-extensions/lib',
                    'Doctrine\Custom\Annotation' => __DIR__ . '/../lib/'
                )
            )
        )
    ),
    'cache' => array(
        'adapter' => 'redis',
        'enabled' => true,
        'config' => array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'namespace' => 'projectNameNamespace'
        )
    ),
    'debug' => false,
    'intl' => array(
        'lang' => 'en_US'
    ),
    'cors' => array(
        'enabled' => false,
        'origin' => ''
    ),
    'token' => array(
        'lifetime' => 480 // minutes
    ),
    'vital' => array(
        'threshold' => 10 // minutes
    ),
    'paths' => array(
        'lang' => __DIR__ . '/../data/lang/',
        'timezone' => __DIR__ . '/../data/timezone/',
    ),
    'date' => array(
        'format' => \DateTime::ISO8601,
        'timezoneIndex' => 151
    ),
    'services' => array(
        'AuthenticationService' => 'Service\Authentication',
        'CacheService' => 'Service\Cache',
        'MessageService' => 'Service\Message',
        'TimezoneService' => 'Service\Timezone',
        'UserService' => 'User\Service\Service',
        'CompanyService' => 'Company\Service\Service',
        'CountryService' => 'Country\Service\Service'
    ),
    'handlers' => array(
        'exception' => 'Handler\Exception'
    ),
    'listeners' => array(
        'Listener\Main'
    ),
    'middlewares' => array(
        'before' => array(
            'Middleware\CrossDomain',
            'Middleware\RequestData',
            'Middleware\Doctrine',
            'Middleware\Authorization',
            'Middleware\Trimmer'
        ),
        'after' => array(
            'Middleware\JsonStrategy',
        ),
        'finish' => array(),
    ),
    'publicRoutes' => array(
        'GET:/token'
    ),
    'routes' => array(
        '/token' => array(
            'controller' => 'Application\Controller\RestController',
            'actions'    => array(
                'getToken' => array(
                    'route' => '',
                    'method' => 'get'
                )
            )
        ),
        '/users' => array(
            'controller' => 'User\Controller\RestController',
            'actions'    => array_merge_recursive($crudActions, array(
                'findByUsername' => array(
                    'route' => '/find-by-username/{username:[a-zA-Z0-9\-]+}}',
                    'method' => 'get'
                ),
            ))
        ),
        '/country' => array(
            'controller' => 'Country\Controller\RestController',
            'actions'    => array_merge_recursive($crudActions, array(
                'findAll' => array(
                    'route' => '',
                    'method' => 'get'
                )
            ))
        ),
    ),
);