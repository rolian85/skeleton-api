<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * Description of DocumentCategory
 *
 * @author Martin
 */

/**
 * @ORM\Entity @ORM\Table(name="document_category")
 */
class DocumentCategory extends BaseEntity implements PersistableBase {

    const ROOT_NAME = "Root";

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Organization")
     */
    protected $organization;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="DocumentCategory", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="category")
     */
    /* protected $documents; */

    function __construct() {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection;
        $this->children = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getOrganization() {
        return $this->organization;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
        $parent->addChild($this);
    }

    public function getChildren() {
        return $this->children;
    }

    public function addChild($documentCategory) {
        if (empty($this->children)) {
            $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->children->add($documentCategory);
    }

    public function isRoot() {
        return $this->name == self::ROOT_NAME;
    }

    public function hasChildren() {
        return count($this->children) > 0;
    }

    public function __toString() {
        $json = "{
           name: $this->getName(),
           children: $this->children
          }";
        return $json;
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

