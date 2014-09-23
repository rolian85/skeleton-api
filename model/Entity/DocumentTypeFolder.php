<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/** @ORM\Entity 
 *  @ORM\Table(name="document_type_folder", uniqueConstraints={@ORM\UniqueConstraint(name="document_type", columns={"company_id", "documentType_id"})}) */
class DocumentTypeFolder extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Company") 
     *  @deprecated Replaced by organization - To be removed in version 1.0.8 */
    protected $company;
    
    /** @ORM\ManyToOne(targetEntity="Organization") */
    protected $organization;

    /** @ORM\ManyToOne(targetEntity="DocumentType") */
    protected $documentType;

    /** @ORM\ManyToOne(targetEntity="DocumentFolder") */
    protected $documentFolder;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    /** @deprecated Replaced by getOrganization() */
    public function getCompany() {
        return $this->company;
    }
    
    /** @deprecated Replaced by setOrganization() */
    public function setCompany($company) {
        $this->company = $company;
    }
    
    public function getOrganization() {
        return $this->organization;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }
    
    public function getDocumentType() {
        return $this->documentType;
    }

    public function setDocumentType($documentType) {
        $this->documentType = $documentType;
    }

    public function getDocumentFolder() {
        return $this->documentFolder;
    }

    public function setDocumentFolder($documentFolder) {
        $this->documentFolder = $documentFolder;
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
} ?>
