<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return parent::findBy($this->fixCriteria($criteria), $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria)
    {
        return parent::findOneBy($this->fixCriteria($criteria));
    }

    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null)
    {
        return $this->findOneBy(array(
            'id' => $id
        ));
    }

    private function fixCriteria(array $criteria)
    {
        //Unless explicitly requested to return deleted items, we want to return non-deleted items by default
        if (!in_array('deleted', $criteria)) {
            $criteria['deleted'] = false;
        }

        return $criteria;
    }

    public function filterByName($pattern = '')
    {
        $dql = "SELECT u FROM \Entity\User u "
                . " WHERE u.deleted=0 AND (u.firstName LIKE '%" . $pattern . "%' OR u.lastName LIKE '%" . $pattern . "%') "
                . " ORDER BY u.firstName, u.lastName ";
        $users = $this->_em->createQuery($dql)->getResult();

        return $users;
    }

    public function filterByGroup($groupName)
    {
        $dql = "SELECT u FROM \Entity\User u "
                . " JOIN u.groups ug "
                . " JOIN ug.group g "
                . " WHERE u.deleted=0 AND g.nameId='$groupName'"
                . " GROUP BY u.id "
                . " ORDER BY u.firstName, u.lastName ";
        $users = $this->_em->createQuery($dql)->getResult();

        return $users;
    }

    public function findSystemUser()
    {
        $this->findOneBy(array('username'=>'system'));
    }

    /**
     * Returns an instance of a user that matches the username
     * @param  string $userName
     * @return \Entity\User
     */
    public function getUserByUserName($userName){

        return $this->findOneBy(array('username'=> $userName));
    }
}