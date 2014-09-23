<?php
namespace Country\Service;

class Service extends \Phalcon\DI\Injectable
{
    public function findAll()
    {
    	return $this->em->getRepository('\Entity\Country')->getCountries();
    }
}