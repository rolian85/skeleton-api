<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity
 * @ORM\Table(name="config")
 */
class Config extends BaseEntity implements PersistableBase {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=36)
     */
    protected $ownerId;

    /** @ORM\Column(name="config_key", type="string", length=64) * */
    protected $key;

    /** @ORM\Column(type="string", length=20) * */
    protected $dataType;

    /** @ORM\Column(type="string", length=255, nullable=true) * */
    protected $dataString;

    /** @ORM\Column(type="integer", nullable=true) * */
    protected $dataInt;

    /** @ORM\Column(type="text", nullable=true) * */
    protected $dataText;

    /** @ORM\Column(type="array", nullable=true) * */
    protected $dataArray;

    /** @ORM\Column(type="string", length=128, nullable=true) * */
    protected $dataEntity;

    /** @ORM\Column(type="text", nullable=true) * */
    protected $dataObject;

    public function getOwnerId() {
        return $this->ownerId;
    }

    public function setOwnerId($ownerId) {
        $this->ownerId = $ownerId;
    }

    public function setDataArray($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function getDataArray() {
        return $this->dataArray;
    }

    public function setDataEntity($dataEntity) {
        $this->dataEntity = $dataEntity;
    }

    public function getDataEntity() {
        return $this->dataEntity;
    }

    public function setDataInt($dataInt) {
        $this->dataInt = $dataInt;
    }

    public function getDataInt() {
        return $this->dataInt;
    }

    public function setDataObject($dataObject) {
        $this->dataObject = $dataObject;
    }

    public function getDataObject() {
        return $this->dataObject;
    }

    public function setDataString($dataString) {
        $this->dataString = $dataString;
    }

    public function getDataString() {
        return $this->dataString;
    }

    public function setDataText($dataText) {
        $this->dataText = $dataText;
    }

    public function getDataText() {
        return $this->dataText;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function getKey() {
        return $this->key;
    }

    public function setDataType($dataType) {
        $this->dataType = $dataType;
    }

    public function getDataType() {
        return $this->dataType;
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