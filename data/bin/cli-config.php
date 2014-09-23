<?php
/**
 * Created by PhpStorm.
 * User: rolian85
 * Date: 9/18/14
 * Time: 11:35 PM
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;
$app = new Phalcon\Mvc\Micro();

// Setting the config
$di = new Phalcon\DI\FactoryDefault();
$di->set("ConfigService", function() {
    $global = new \Phalcon\Config(require(__DIR__ . '/../../config/global.php'));
    $local = new \Phalcon\Config(require(__DIR__ . '/../../config/local.php'));

    $global->merge($local);
    return $global;
});
$config = $di->get('ConfigService');
$app->setDI($di);

// Auto-loading
$loader = new \Phalcon\Loader();
$loader->registerDirs($config->autoload->toArray())->register();
require_once __DIR__ . '/../../vendor/autoload.php';

// Registering Services
foreach($config->services->toArray() as $service => $class) {
    $di->set($service, function() use($di, $class) {
        $serviceObject = new $class();
        if(method_exists($serviceObject,'setDi')) {
            $serviceObject->setDi($di);
        }
        return $serviceObject;
    }, true);
}

// Setting doctrine
$doctrineMiddleware = new \Middleware\Doctrine();
$doctrineMiddleware->call($app);

return ConsoleRunner::createHelperSet($di->get('em'));