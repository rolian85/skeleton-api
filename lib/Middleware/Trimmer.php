<?php
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class Trimmer implements MiddlewareInterface
{
    public function call($app)
    {
        if(isset($app->request->data)) {
            $filter = new \Phalcon\Filter();
            array_walk_recursive($app->request->data, function(&$value) use($filter) {
                if(is_string($value)) {
                    $value = $filter->sanitize($value, 'trim');
                }
            });
        }
    }
} 