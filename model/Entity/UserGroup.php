<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * @ORM\Entity @ORM\Table(name="usergroup")
 */
class UserGroup extends BaseEntity implements PersistableBase {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=50))
     */
    protected $name;

    /** ManyToOne(targetEntity="Organization", inversedBy="userGroups") */
    protected $organization;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    protected $homepage;

    /**
     * @ORM\Column(type="string",length=70, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string",length=70, nullable=true)
     * 
     */
    protected $nameId;

    /**
     * @ORM\OneToMany(targetEntity="UsersGroups", mappedBy="group")
     */
    protected $users;

    /** @ORM\OneToMany(targetEntity="UserGroupPermission", mappedBy="group") */
    protected $grouproles;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $contactable;

    /**
     * @return the $nameId
     */
    public function getNameId() {
        return $this->nameId;
    }

    /**
     * @param field_type $nameId
     */
    public function setNameId($nameId) {
        $clean_name = str_replace(" ", "_", $nameId);

        $this->nameId = strtoupper($clean_name);
    }

    /**
     * @return the $grouproles
     */
    public function getGrouproles() {
        return $this->grouproles;
    }

    /**
     * @return the $grouproles
     */
    public function getPermissions() {
        return $this->grouproles;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $grouproles
     */
    public function setGrouproles($grouproles) {
        $this->grouproles = $grouproles;
    }

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grouproles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        $this->setNameId($name);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsers() {
        return $this->users;
    }

    public function getUsersArray() {
        $usr = array();

        foreach ($this->users as $value) {
            $value instanceof \User;
            $usr[] = $value->getId();
        }
        return $usr;
    }

    public function getOrganization() {
        return $this->organization;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    public function addUser(User $user) {
        $this->users->add($user);
    }


    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function toArray() {
        $result = array();

        $result['id'] = $this->getId();
        $result['name'] = $this->getName();
        //$result['users'] = $this->getUsersArray();
        $result['description'] = $this->getDescription();
        $result['email'] = $this->getEmail();
        $result['roles'] = $this->getGrouproles()->toArray();

        return $result;
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
    
    public function getHomepage() {
        return $this->homepage;
    }

    public function setHomepage($homepage) {
        $this->homepage = $homepage;
    }

    public function setContactable($contactable)
    {
        $this->contactable = $contactable;
    }

    public function isContactable()
    {
        return $this->contactable == true;
    }


}