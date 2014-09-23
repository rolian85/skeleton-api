<?php
namespace Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity @ORM\Table(name="organization")
 */
class Organization extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="organization")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $companies;

    /*
     * @ORM\OneToMany(targetEntity="UserGroup", mappedBy="organization")
     */
    protected $userGroups;
    
    /** @ORM\OneToMany(targetEntity="DocumentFolder", mappedBy="organization") */
    protected $documentFolders;

    public function __construct() {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function getAllergyQL() {
        return $this->allergiesQL;
    }

    public function getUserGroups() {
        return $this->userGroups;
    }

    public function setUserGroups($userGroups) {
        $this->userGroups = $userGroups;
    }
        
    public function getCompanies() {
        return $this->companies;
    }

    public function setCompanies($companies) {
        $this->companies = $companies;
    }
    
    public function getDocumentFolders() {
        return $this->documentFolders;
    }

    public function setDocumentFolders($documentFolders) {
        $this->documentFolders = $documentFolders;
    }
        
    public function toArray() {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName()
        );
    }

    public function auditable() {
        return $this->getId();
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