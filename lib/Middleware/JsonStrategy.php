<?php 
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class JsonStrategy implements MiddlewareInterface
{
	public function call($app)
	{
        if(isset($app->response->json)) {
            $app->response->setHeader("Content-Type", "application/json")->sendHeaders();
            echo json_encode($app->response->json);
        }
	}
}