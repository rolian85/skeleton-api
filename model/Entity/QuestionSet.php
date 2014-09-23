<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity(repositoryClass="Repository\QuestionSet")
 * @ORM\Table(name="question_set")
 */
class QuestionSet extends BaseEntity implements PersistableBase {

    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=50, nullable=false) */
    protected $name;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $nameId;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $grouping;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $ordering;

    /** @ORM\OneToMany(targetEntity="Question", mappedBy="questionSet")
     *  @ORM\OrderBy({"ordering" = "ASC", "name" = "ASC"}) */
    protected $questions;

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

    public function getGrouping() {
        return $this->grouping;
    }

    public function setGrouping($grouping) {
        $this->grouping = $grouping;
    }

    public function getOrdering() {
        return $this->ordering;
    }

    public function setOrdering($ordering) {
        $this->ordering = $ordering;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
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

    public function toArray($level = self::HYDRATION_LEVEL_BASIC)
    {
        $result = array();
        $result['id'] = $this->getId();
        $result['name'] = $this->getName();
        $result['nameId'] = $this->getNameId();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['questions'] = \Helper\EntityCollection::toArray($this->getQuestions(), self::HYDRATION_LEVEL_ADVANCED);
        }

        return $result;
    }
}