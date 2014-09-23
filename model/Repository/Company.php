<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;

class Company extends EntityRepository {
    
    function getAll()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->add('select', 'c');
        $qb->add('from', '\Entity\Company c');

        $result = $this->_em->createQuery($qb)->getResult();

        return $result;
    }
    
}

?>
