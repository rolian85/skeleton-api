<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/** @ORM\Entity(repositoryClass="Repository\DocumentFolder") @ORM\Table(name="document_folder") */
class DocumentFolder extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $description;

    /** @ORM\ManyToOne(targetEntity="Organization", inversedBy="documentFolders") */
    protected $organization;
    
    /** @ORM\ManyToOne(targetEntity="Company", inversedBy="documentFolders") 
     *  @deprecated Replaced by organization - To be removed in version 1.0.8
     */
    protected $company;

    /** @ORM\ManyToOne(targetEntity="DocumentFolder", inversedBy="children") */
    protected $parent;

    /** @ORM\OneToMany(targetEntity="DocumentFolder", mappedBy="parent") */
    protected $children;

    /** @ORM\OneToMany(targetEntity="Document", mappedBy="folder") */
    protected $documents;

    /** @ORM\Column(type="boolean") */
    protected $deleted = 0;

    /** @ORM\Column(type="boolean") */
    protected $canBeDeleted = true;

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

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
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

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getOrganization() {
        return $this->organization;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }
    
    /** @deprecated Replaced by getOrganization() */
    public function getCompany() {
        return $this->company;
    }
    
    /** @deprecated Replaced by setOrganization() */
    public function setCompany($company) {
        $this->company = $company;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getChildren() {
        return $this->children;
    }

    public function setChildren($children) {
        $this->children = $children;
    }

    public function getDocuments() {
        return $this->documents;
    }

    public function getActiveDocuments($patient = null) {
        $em = \Common\Helper\Helper::getEntityManager();
        return $em->getRepository('\Entity\DocumentFolder')->getActiveDocuments($this, $patient);
    }

    public function setDocuments($documents) {
        $this->documents = $documents;
    }

    public function delete() {
        if ($this->canBeDeleted)
            $this->deleted = true;
        else
            throw new \Exception("The '{$this->getName()}' document folder can not be deleted", 0, null);
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function canBeDeleted() {
        return $this->canBeDeleted;
    }

    public function setCanBeDeleted($canBeDeleted) {
        $this->canBeDeleted = $canBeDeleted;
    }

    public function auditable() {
        return $this->getId();
    }

}

?>