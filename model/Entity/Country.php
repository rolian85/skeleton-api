<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity(repositoryClass="Repository\Country")
 * @ORM\Table(name="country")
 */
class Country extends BaseEntity implements PersistableBase {

    /** @ORM\Id @ORM\Column(type="string", length=2) */
    protected $iso;

    /** @ORM\Column(type="string", length=80) */
    protected $name;

    /** @ORM\Column(type="string", length=80) */
    protected $printable_name;

    /** @ORM\Column(type="string", length=3, nullable=true) */
    protected $iso3;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $numcode;

    /*
     * @ORM\OneToMany(targetEntity="City")
     * @ORM\JoinColumn(name="iso", referencedColumnName="country_id")
     */
    protected $cities;

    public function toArray() {
        return array(
            'id' => $this->getIso(),
            'iso3' => $this->getIso3(),
            'code' => $this->getNumcode(),
            'nameKey' => $this->getName(),
            'name' => $this->getPrintable_name()
        );
    }

    public function setCities($cities) {
        $this->cities = $cities;
    }

    public function getCities() {
        return $this->cities;
    }

    public function setIso($iso) {
        $this->iso = $iso;
    }

    public function getIso() {
        return $this->iso;
    }

    public function setIso3($iso3) {
        $this->iso3 = $iso3;
    }

    public function getIso3() {
        return $this->iso3;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setNumcode($numericCode) {
        $this->numericCode = $numericCode;
    }

    public function getNumcode() {
        return $this->numericCode;
    }

    public function getPrintable_name() {
        return $this->printable_name;
    }

    public function setPrintable_name($printable_name) {
        $this->printable_name = $printable_name;
    }

    public function __toString() {
        return $this->getIso();
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