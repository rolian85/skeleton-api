<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;

class DocumentFolder extends EntityRepository {
    
    public function getActiveDocuments(\Entity\DocumentFolder $folder) {
        $qb = $this->_em->createQueryBuilder();
        
        $query = $qb->select('d')
                    ->from('\Entity\Document', 'd')
                    ->leftJoin('d.assignedUsers', 'u')
                    ->where('d.folder = ?1')
                    ->andWhere('d.deleted = 0');
        $query->setParameter(1, $folder);

        return $query->getQuery()->getResult();
    }
    
    public function getRootFolder($level) {
        $name = "GLOBAL_ROOT";
        return $this->findOneBy(array("name" => $name, 
                                      "canBeDeleted" => false));
    }
    
    public function getGlobalRootFolder() {
        return $this->getRootFolder("global");
    }
    
    public function getGlobalUnsortedFolder() {
        $globalRootFolder = $this->getGlobalRootFolder();        
        return $this->findOneBy(array("name" => "Unsorted", 
                                      "canBeDeleted" => false,
                                      "parent" => $globalRootFolder));
    }

}