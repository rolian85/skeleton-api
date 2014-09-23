<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * @ORM\Entity @ORM\Table(name="question_input_value")
 */
class QuestionInputValue extends BaseEntity implements PersistableBase{

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="QuestionInput", inversedBy="values", fetch="EAGER")
     *  @ORM\JoinColumn(name="questionInput_id", referencedColumnName="id", onDelete="CASCADE") */
    protected $questionInput;
    
    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $grouping;
    
    /** @ORM\Column(type="string", length=50, nullable=false) */
    protected $value;
    
    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $description;
    
    /** @ORM\Column(type="integer", nullable=false) */
    protected $ordering = 100;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getQuestionInput() {
        return $this->questionInput;
    }

    public function setQuestionInput($questionInput) {
        $this->questionInput = $questionInput;
    }
    
    public function getGrouping() {
        return $this->grouping;
    }

    public function setGrouping($grouping) {
        $this->grouping = $grouping;
    }
    
    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getDescription() {
        if(is_null($this->description))
            return $this->value;
        
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getOrdering() {
        return $this->ordering;
    }

    public function setOrdering($ordering) {
        $this->ordering = $ordering;
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

    public function toArray($level = null)
    {
        $result = array();
        $result['id'] = $this->getId();
        $result['value'] = $this->getValue();
        $result['grouping'] = $this->getGrouping();
        $result['description'] = $this->getDescription();

        return $result;
    }
}