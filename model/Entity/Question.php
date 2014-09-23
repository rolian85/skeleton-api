<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
/**
 * @ORM\Entity @ORM\Table(name="question")
 */
class Question extends BaseEntity implements PersistableBase{

    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255, nullable=false) */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $nameKey;

    /** @ORM\ManyToOne(targetEntity="QuestionSet", inversedBy="questions", fetch="EAGER")
     *  @ORM\JoinColumn(name="questionSet_id", referencedColumnName="id", onDelete="CASCADE") */
    protected $questionSet;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $grouping;
    
    /** @ORM\Column(type="integer", nullable=false) */
    protected $ordering = 100;
    
    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $displayFormat;
    
    /** @ORM\Column(type="array", nullable=true) */
    protected $attrs;
    
    /** @ORM\OneToMany(targetEntity="QuestionInput", mappedBy="question")
     *  @ORM\OrderBy({"ordering" = "ASC"}) */
    protected $inputs;

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

    public function setNameKey($nameKey)
    {
        $this->nameKey = $nameKey;
    }

    public function getNameKey()
    {
        return $this->nameKey;
    }
    
    public function getQuestionSet() {
        return $this->questionSet;
    }

    public function setQuestionSet($questionSet) {
        $this->questionSet = $questionSet;
    }

    public function setGrouping($grouping)
    {
        $this->grouping = $grouping;
    }

    public function getGrouping()
    {
        return $this->grouping;
    }
    
    public function getOrdering() {
        return $this->ordering;
    }

    public function setOrdering($ordering) {
        $this->ordering = $ordering;
    }
    
    public function getDisplayFormat() {
        return $this->displayFormat;
    }

    public function setDisplayFormat($displayFormat) {
        $this->displayFormat = $displayFormat;
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
            
    public function getInputs() {
        return $this->inputs;
    }

    public function setInputs($inputs) {
        $this->inputs = $inputs;
    }
    
    public function formatAnswers(array $answers) {
        $displayFormat = $this->getDisplayFormat();
        if(is_null($displayFormat) || $displayFormat == '')
            return implode(" ", $answers);
        else {
            for($i = 0; $i < count($answers); $i++) {
                $matches = array();
                $pattern = '/\{([^\{]*)\{i'.$i.'\}([^\{]*)\}/';
                if(preg_match($pattern, $displayFormat, $matches))
                    $displayFormat = preg_replace($pattern, $matches[1].$answers[$i].$matches[2], $displayFormat);
            }
                
            return trim(preg_replace('/\{([^\{]*)\{i\d\}([^\{]*)\}/', "", $displayFormat));
        }
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
        $result['name'] = $this->getName();
        $result['nameId'] = $this->getNameKey();
        $result['grouping'] = $this->getGrouping();
        $result['displayFormat'] = $this->getDisplayFormat();
        $result['ordering'] = $this->getOrdering();
        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['inputs'] = \Helper\EntityCollection::toArray($this->getInputs(), self::HYDRATION_LEVEL_ADVANCED);
        }
        return $result;
    }
}