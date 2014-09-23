<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Query\Expr\Base;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity @ORM\Table(name="resource")
 */
class Resource extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\OneToMany(targetEntity="PermissionResource", mappedBy="resource") */
    protected $permissions;

    /** @ORM\Column(type="string", length=255) */
    protected $controller;

    /** @ORM\Column(type="string", length=255) */
    protected $module;

    /** @ORM\Column(type="string", length=255) */
    protected $action;

    /** @ORM\Column(type="boolean") */
    protected $haslayout = true;

    /**
     * @param field_type $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getAction() {
        return $this->action;
    }

    public function getModule() {
        return $this->module;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setModule($module) {
        $this->module = $module;
        return $this;
    }

    public function getController() {
        return $this->controller;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }

    public function getHaslayout() {
        return $this->haslayout;
    }

    public function setHaslayout($haslayout) {
        $this->haslayout = $haslayout;
    }

    public function toArray() {
        return array(
            'id' => $this->getId(),
            'controller' => $this->getController(),
            'module' => $this->getModule(),
            'action' => $this->getAction(),
            'users' => $this->getUsers()->toArray(),
            'groups' => $this->getGroups()->toArray()
        );
    }

    public function auditable() {
        return $this->getId();
    }

    public function toString() {
        return $this->module . "/" . $this->controller . "/" . $this->action;
    }

    /**
     * -------------------------------------
     * Implementation of PersistableBase
     * -------------------------------------
     */

    /** @ORM\Column(type="boolean") */
    protected $deleted = false;

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

