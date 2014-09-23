<?php 
namespace Middleware;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class Authorization implements MiddlewareInterface 
{
	public function call($app)
	{
        if($app->request->getMethod() == 'OPTIONS') {
            return;
        }

        $uri = $app->request->getMethod() . ':' . $app->getRouter()->getRewriteUri();
        $publicRoutes = $app->getService('ConfigService')->publicRoutes->toArray();
        if(!in_array($uri, $publicRoutes)) {
            $token = $app->request->getHeader('HTTP_ACCESS_TOKEN');
            if(empty($token)) {
                $token = isset(getallheaders()['access_token']) ? getallheaders()['access_token'] : '';
            }

            if(empty($token)) {
                throw new \Exception\TokenRequired();
            }

            $validToken = false;
            $cacheService = $app->getService('CacheService');
            if($cacheService->isEnabled()) {
                $user = $cacheService->fetch(\Helper\CacheRegistry::KEY_ACCESS_TOKEN . $token);
                $validToken = is_object($user);
            }

            if(!$validToken) {
                $user = $app->getService('UserService')->findOneByToken($token);
                $validToken = is_object($user) && !$user->isTokenExpired();
            }

            if(!$validToken) {
                throw new \Exception\TokenInvalid();
            }

            $userService = $app->getService('UserService');
            $userService->setCurrentUser($user);
            \Doctrine\Custom\Event\Listener::setCreator($userService->getCurrentUser(true));
        }
	}
}