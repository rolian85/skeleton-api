<?php
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class RequestData implements MiddlewareInterface
{
    public function call($app)
    {
        if($app->request->isPost() || $app->request->isPut()) {
            $requestBody = file_get_contents('php://input');
            $app->request->data = json_decode($requestBody, true);
            if(!is_array($app->request->data)) {
                $app->request->data = array();
            }
        }
    }
} 