<?php
namespace Company\Service;

class Service extends \Phalcon\DI\Injectable
{
    public function findById($id)
    {
        return $this->em->find('\Entity\Company', $id);
    }
}