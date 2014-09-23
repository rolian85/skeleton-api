<?php
/**
 * Created by PhpStorm.
 * User: rolian85
 * Date: 9/18/14
 * Time: 11:43 PM
 */

$app = new Phalcon\Mvc\Micro();
// Setting the config
$di = new Phalcon\DI\FactoryDefault();
$di->set("ConfigService", function() {
    $global = new \Phalcon\Config(require(__DIR__ . '/../config/global.php'));
    $localConfigFile = __DIR__ . '/../config/local.php';
    if(file_exists($localConfigFile)) {
        $local = new \Phalcon\Config(require(__DIR__ . '/../config/local.php'));
        $global->merge($local);
    }

    return $global;
});
$config = $di->get('ConfigService');
$app->setDI($di);
// Setting php settings
foreach($config->phpSettings->toArray() as $setting => $value) {
    ini_set($setting, $value);
}
// Auto-loading
$loader = new \Phalcon\Loader();
$loader->registerDirs($config->autoload->toArray())->register();
require_once __DIR__ . '/../vendor/autoload.php';
// Registering Services
foreach($config->services->toArray() as $service => $class) {
    $di->set($service, $class, true);
}

// Registering listeners
$eventsManager = new Phalcon\Events\Manager();
foreach($config->listeners->toArray() as $listenerClass) {
    $listener = new $listenerClass();
    $eventsManager->attach('micro', $listener);
}
$app->setEventsManager($eventsManager);
// Cross Domain Pre-flight
// @TODO - Find a way to do the cross domain headers globally instead of in 3 different places (2 here and one in the CrossDomain middleware)
if($config->cors->enabled) {
    $app->options('/{catch:(.*)}', function() use ($app, $config) {
        $app->response->setHeader("Access-Control-Allow-Origin", $config->cors->origin)
            ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE')
            ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization, access_token')
            ->setHeader("Access-Control-Allow-Credentials", true)
            ->setStatusCode(200, "OK")
            ->send();
    });
}
// Registering middlewares
foreach($config->middlewares->toArray() as $action => $middlewares) {
    foreach($middlewares as $middlewareClass) {
        $app->$action(new $middlewareClass());
    }
}
// Registering routes
foreach($config->routes->toArray() as $prefix => $options) {
    $collection = new Phalcon\Mvc\Micro\Collection();
    $collection->setHandler($options['controller'], true);
    $collection->setPrefix($prefix);

    foreach($options['actions'] as $action => $actionOptions) {
        $method = $actionOptions['method'];
        $collection->$method($actionOptions['route'], $action);
    }
    $app->mount($collection);
}

// Registering handlers
$app->notFound(function () use ($app, $config){
    if($config->cors->enabled) {
        $app->response->setHeader("Access-Control-Allow-Origin", $config->cors->origin)
            ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE')
            ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization, access_token')
            ->setHeader("Access-Control-Allow-Credentials", true);
    }

    throw new \Exception\UrlNotFoundException();
});
register_shutdown_function(function() use($app, $config) {
    $error = error_get_last();
    if($error !== NULL && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR,
            E_COMPILE_ERROR, E_USER_ERROR,
            E_RECOVERABLE_ERROR))) {

        $e = new \Exception\PhpErrorException($error["message"]);
        $e->setFile($error['file']);
        $e->setLine($error['line']);
        $handler = $config->handlers->exception;
        $handler::handle($app, $e);
    }
});

try {
    // Running application
    $app->handle();
} catch (\Exception $e) {
    $handler = $config->handlers->exception;
    $handler::handle($app, $e);
}
