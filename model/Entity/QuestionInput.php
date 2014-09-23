<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * @ORM\Entity @ORM\Table(name="question_input")
 */
class QuestionInput extends BaseEntity implements PersistableBase{

    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Question", inversedBy="inputs", fetch="EAGER")
     *  @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE") */
    protected $question;

    /** @ORM\Column(type="string", length=50, nullable=false) */
    protected $type;

    /** @ORM\Column(type="array", nullable=true) */
    protected $attrs;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $suffix;

    /** @ORM\Column(type="integer", nullable=false) */
    protected $ordering = 100;

    /** @ORM\OneToMany(targetEntity="QuestionInputValue", mappedBy="questionInput") 
     *  @ORM\OrderBy({"grouping" = "ASC", "ordering" = "ASC", "description" = "ASC", "value" = "ASC"}) */
    protected $values;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getAttrs() {
        return $this->attrs;
    }

    public function setAttrs($attrs) {
        $this->attrs = $attrs;
    }

    public function addAttrs($newAttrs) {
        if(is_array($newAttrs)) {
            $existingAttrs = $this->getAttrs();
            if(!is_array($existingAttrs))
                $this->setAttrs($newAttrs);
            else {
                foreach($newAttrs as $key => $value) {
                    if(isset($existingAttrs[$key]))
                        $existingAttrs[$key] .= " ".$newAttrs[$key];
                    else
                        $existingAttrs[$key] = $newAttrs[$key];
                }
                $this->setAttrs($existingAttrs);
            }
        }
    }

    public function getAttrsString() {
        if(!is_array($this->attrs))
            return null;
        
        $tmpArray = array();
        foreach($this->attrs as $key => $value)
            $tmpArray[] = "{$key} = '{$value}'";
        return implode(" ", $tmpArray);
    }

    public function getSuffix() {
        return $this->suffix;
    }

    public function setSuffix($suffix) {
        $this->suffix = $suffix;
    }

    public function getOrdering() {
        return $this->ordering;
    }

    public function setOrdering($ordering) {
        $this->ordering = $ordering;
    }

    public function getValues() {
        return $this->values;
    }

    public function setValues($values) {
        $this->values = $values;
    }

    public function getValue() {
        foreach($this->getValues() as $value)
            return $value->getValue();
        
        return null;
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
        $result['type'] = $this->getType();
        $result['attrs'] = $this->getAttrs();
        $result['attrsString'] = $this->getAttrsString();
        $result['suffix'] = $this->getSuffix();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['values'] = \Helper\EntityCollection::toArray($this->getValues());
        }

        return $result;
    }
}