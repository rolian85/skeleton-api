<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * Description of UserGroupPermission
 *
 * @author ypmartin
 */

/**
 * @ORM\Entity(repositoryClass="Repository\UserGroupPermission")
 * @ORM\Table(name="user_group_permission")
 */
class UserGroupPermission extends BaseEntity implements PersistableBase{


    /** @ORM\Id @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="grouproles") */
    protected $group;

    /** @ORM\Id @ORM\ManyToOne(targetEntity="Permission", inversedBy="groups") */
    protected $permission;

    /** @ORM\Column(type="boolean") */
    protected $deleted = false;
    
    /**
     * @return the $group
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * @param field_type $group
     */
    public function setGroup($group) {
        $this->group = $group;
    }

    public function getPermission() {
        return $this->permission;
    }

    public function setPermission($permission) {
        $this->permission = $permission;
    }

    /**
     * -------------------------------------
     * Implementation of PersistableBase
     * -------------------------------------
     */
    public function delete() {
        $this->deleted = true;
    }

    public function isDeleted() {
        return $this->deleted;
    }
    
    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }

    
    /**
     * -------------------------------------
     * TIMESTAMPABLE CODE
     * -------------------------------------
     */

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @UpdateTimezone(field="createdTimeZone")
     */
    protected $created;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $createdTimeZone;

    public function getCreated($useTimezone = true) {
        return $this->_getDate($this->created, $this->getCreatedTimeZone(), $useTimezone);
    }

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @UpdateTimezone(field="updatedTimeZone")
     */
    protected $updated;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $updatedTimeZone;

    public function getUpdated($useTimezone = true) {
        return $this->_getDate($this->updated, $this->getUpdatedTimeZone(), $useTimezone);
    }

    public function getCreatedTimeZone() {
        return $this->createdTimeZone;
    }

    public function getUpdatedTimeZone() {
        return $this->updatedTimeZone;
    }
}

