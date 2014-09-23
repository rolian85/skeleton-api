<?php
namespace User\Service;

class Service extends \Phalcon\DI\Injectable
{
    private $_currentUser;

    public function findById($id)
    {
        return $this->em->find('\Entity\User', $id);
    }

    public function setCurrentUser(\Entity\User $currentUser)
    {
        $this->_currentUser = $currentUser;
    }

    public function getCurrentUser($persistable = false)
    {
        if(is_object($this->_currentUser)) {
            return ($persistable) ? $this->em->getReference('\Entity\User', $this->_currentUser->getId()) : $this->_currentUser;
        }

        return null;
    }

    public function getCurrentUserId()
    {
        $user = $this->getCurrentUser();
        return (is_object($user)) ? $user->getId() : null;
    }

    public function findOneByUsername($username)
    {
        return $this->em->getRepository('\Entity\User')->findOneByUsername($username);
    }

    public function findOneByToken($token)
    {
        return $this->em->getRepository('\Entity\User')->findOneByToken($token);
    }
}