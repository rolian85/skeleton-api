<?php
namespace QuestionSet\Service;

class Service extends \Phalcon\DI\Injectable
{
    public function findByGroup($groupKey)
    {
        return $this->em->getRepository('\Entity\QuestionSet')->findBy(array("grouping" => $groupKey),
                array("ordering" => "ASC"));
    }

    public function findOneByNameKey($nameKey)
    {
        return $this->em->getRepository('\Entity\QuestionSet')->findOneByNameId($nameKey);
    }

    public function findAll()
    {
        return $this->em->getRepository('\Entity\QuestionSet')->findAll();
    }
}