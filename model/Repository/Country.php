<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Paginate\Paginate;

class Country extends EntityRepository {

    public function getCountries()
    {
        return $this->_em->createQuery("SELECT c FROM \Entity\Country c ORDER BY c.printable_name")->getResult();
    }
}