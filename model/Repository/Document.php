<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;

class Document extends EntityRepository {

    public function findByIds($ids, $includePatient = false)
    {
        $select = ($includePatient) ? "d, p" : "d";
        $join = ($includePatient) ? "LEFT JOIN d.patient p" : "";

        return $this->_em->createQuery("SELECT {$select} FROM \Entity\Document d {$join}
                                        WHERE d.id IN (?1)")
                         ->setParameter(1, $ids)
                         ->getResult();
    }
}