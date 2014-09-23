<?php
namespace Application\Controller;

class RestController extends \Phalcon\Mvc\Controller
{
    public function getToken()
    {
        $auth = $this->request->getBasicAuth();
        if(!is_array($auth)) {
            throw new \Exception\BadRequestException($this->MessageService->get('httpAuthenticationRequired'));
        }

        $authenticated = false;
        $user = $this->UserService->findOneByUsername($auth['username']);
        $message = null;
        if(is_object($user)) {
            if($user->isLocked()) {
                $message = $this->MessageService->get('accountLocked');
            } else {
                $authenticated = $user->passwordMatches($auth['password']);
            }
        }

        if($authenticated) {
            $token = $this->security->getToken(16);
            $expires = new \DateTime();
            $expires->add(new \DateInterval('PT'.$this->ConfigService->token->lifetime.'M'));

            $user->setToken($token);
            $user->setTokenExpired($expires);
            $this->em->persist($user);
            $this->em->flush();

            if($this->CacheService->isEnabled()) {
                $this->CacheService->save(\Helper\CacheRegistry::KEY_ACCESS_TOKEN . $token, $user, $this->ConfigService->token->lifetime * 60);
            }

            $this->response->json = array(
                'token' => $token,
                'expires' => $expires->format($this->ConfigService->date->format)
            );
        } else {
            $message = (!is_null($message)) ? $message : $this->MessageService->get('invalidCredentials');
            throw new \Exception\UnauthorizedException($message);
        }
    }
}