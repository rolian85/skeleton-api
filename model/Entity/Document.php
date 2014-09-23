<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/** @ORM\Entity(repositoryClass="Repository\Document")
 *  @ORM\Table(name="document") */
class Document extends BaseEntity implements PersistableBase, Auditable
{
    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $description;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $filePath;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $fileName;

    /** @ORM\ManyToOne(targetEntity="DocumentType") */
    protected $type;

    /** @ORM\Column(type="string", length=25) */
    protected $mimeType;

    /** @ORM\ManyToOne(targetEntity="DocumentFolder", inversedBy="documents") */
    protected $folder;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")  
     * @UpdateTimezone(field="dateTimeZone") 
     */
    protected $date;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $dateTimeZone;

    /** @ORM\ManyToMany(targetEntity="User", mappedBy="assignedDocuments") */
    protected $assignedUsers;

    /** @ORM\Column(type="boolean") */
    protected $deleted = 0;

    /** @ORM\Column(type="string", length=36, nullable=false) */
    protected $authority_id;

    /** @ORM\Column(type="string", length=36, nullable=false) */
    protected $hash;

    /** @ORM\OneToMany(targetEntity="DocumentMetadata", mappedBy="document") */
    protected $metadata;

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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $shoreIndexUpdated;

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
        $this->assignedUsers       = new ArrayCollection();
        $this->metadata = new ArrayCollection();
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
        $this->setShoreIndexUpdated(false);
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->setShoreIndexUpdated(false);
        $this->description = $description;
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function setFilePath($filePath) {
        $this->filePath = $filePath;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->setShoreIndexUpdated(false);
        $this->type = $type;
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    public function getFolder() {
        return $this->folder;
    }

    public function setFolder($folder) {
        $this->folder = $folder;
    }
    
    public function getDate($useTimezone = false) {
        return $this->_getDate($this->date, $this->getDateTimeZone(), $useTimezone);
    }

    public function setDate($date) {
        $this->setShoreIndexUpdated(false);
        $this->date = $date;
    }

    public function getDateTimeZone() {
        return $this->dateTimeZone;
    }    

    public function setDateTimeZone($dateTimeZone) {
        $this->dateTimeZone = $dateTimeZone;
    }

    public function getAssignedUsers() {
        return $this->assignedUsers;
    }

    public function setAssignedUsers($assignedUsers) {
        $this->assignedUsers = $assignedUsers;
    }

    public function delete() {
        $this->setShoreIndexUpdated(false);
        $this->deleted = true;
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function setAuthorityId($authority_id) {
        $this->authority_id = $authority_id;
    }

    public function getAuthorityId() {
        return $this->authority_id;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getFullFilePath() {
        return \Common\Helper\IDocument::getDocumentRootPath()
                . $this->filePath
                . $this->fileName;
    }

    public function fileExists() {
        return file_exists($this->getFullFilePath());
    }

    public function isPDF() {
        return $this->getMimeType() == "application/pdf";
    }
    
    public function isExcel() {
        return $this->getMimeType() == 'application/vnd.ms-excel';
    }

    public function getFileExtension() {
        $fileNameParts = explode(".", $this->getFileName());
        if (count($fileNameParts) <= 1)
            return "";
        else
            return ".{$fileNameParts[count($fileNameParts) - 1]}";
    }

    public function getDeleted() {
        return $this->deleted;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }

    public function auditable() {
        return $this->getId();
    }

    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function toDocumentSearchArray()
    {
        $result = array();
        $result['id'] = $this->getId();
        $result['name'] = $this->getName();

        if($this->getType() instanceof \Entity\DocumentType)
            $result['type'] = $this->getType()->getId();

        $date = $this->getDate(false);
        if($date instanceof \DateTime) {
            $date = clone $date;
            $date->setTime(0, 0, 0);
            $result['date'] = $date->getTimestamp();
        }

        if(!is_null($this->getDescription()))
            $result['description'] = $this->getDescription();

        foreach($this->getMetadata() as $metadata) {
            $value = ($metadata->isDeleted()) ? null : $metadata->getAnswer(false);
            if($value instanceof \DateTime) {
                $value = clone $value;
                $value->setTime(0, 0, 0);
                $value = $value->getTimestamp();
            }

            $result['metadata_'.$metadata->getQuestion()->getId().'_'.$metadata->getInput()->getId()] = $value;
        }

        $result['active'] = ($this->isDeleted()) ? 0 : 1;

        return $result;
    }

    public function toArray($level = self::HYDRATION_LEVEL_BASIC)
    {
        $result = array();
        $result['id'] = $this->getId();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['type'] = (is_object($type = $this->getType())) ? $type->toArray() : null;
            $result['folder'] = (is_object($folder = $this->getFolder())) ? $folder->toArray() : null;
        }

        $result['file'] = array(
            'path' => $this->getFilePath(),
            'name' => $this->getFileName(),
            'mime' => $this->getMimeType()
        );

        $result['date'] = $this->_formatDate($this->getDate());
        $result['name'] = $this->getName();
        $result['description'] = $this->getDescription();

        return $result;
    }
}