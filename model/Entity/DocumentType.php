<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * Description of DocumentType
 *
 * @author Martin
 */

/**
 * @ORM\Entity(repositoryClass="Repository\DocumentType")
 * @ORM\Table(name="document_type")
 */
class DocumentType extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $name;
    
    /** @ORM\Column(type="string", length=50) */
    protected $nameId;

    /** @ORM\Column(type="string", length=255) */
    protected $description;

    /*
     * @ORM\OneToMany(targetEntity="Document", mappedBy="type")
     */
    protected $documents;
    
    /**
    * @ORM\ManyToOne(targetEntity="QuestionSet")
    */
    protected $questionSet;

    function __construct() {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function getNameId() {
        return $this->nameId;
    }

    public function setNameId($nameId) {
        $this->nameId = $nameId;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDocuments() {
        return $this->documents;
    }

    public function addDocument($document) {
        if (empty($this->documents)) {
            $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->documents->add($document);
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
    
    public function getQuestionSet() {
        return $this->questionSet;
    }

    public function setQuestionSet($questionSet) {
        $this->questionSet = $questionSet;
    }

}

