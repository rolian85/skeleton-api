<?php 
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class CrossDomain implements MiddlewareInterface
{
	public function call($app)
	{
        $configService = $app->getService('ConfigService');
        if($configService->cors->enabled) {
            $app->response->setHeader("Access-Control-Allow-Origin", $configService->cors->origin)
                ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE')
                ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization, access_token')
                ->setHeader("Access-Control-Allow-Credentials", true);
        }
	}
}