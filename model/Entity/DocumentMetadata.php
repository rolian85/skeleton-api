<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity(repositoryClass="Repository\DocumentAnswer")
 * @ORM\Table(name="document_metadata")
 */
class DocumentMetadata extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="metadata")
     */
    protected $document;

    /** @ORM\ManyToOne(targetEntity="Question") */
    protected $question;

    /** @ORM\ManyToOne(targetEntity="QuestionInput") */
    protected $input;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $stringAnswer;

    /** @ORM\Column(type="decimal", scale=2, nullable=true) */
    protected $numericAnswer;

    /** @ORM\Column(type="date", nullable=true)
     *  @UpdateTimezone(field="dateAnswerTimeZone") */
    protected $dateAnswer;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $dateAnswerTimeZone;

    /** @ORM\Column(type="text", nullable=true) */
    protected $textAnswer;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDocument() {
        return $this->document;
    }

    public function setDocument($document) {
        $this->document = $document;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }

    public function getInput() {
        return $this->input;
    }

    public function setInput($input) {
        $this->input = $input;
    }

    public function getStringAnswer() {
        return $this->stringAnswer;
    }

    public function setStringAnswer($stringAnswer) {
        $this->stringAnswer = $stringAnswer;
    }

    public function getNumericAnswer() {
        return $this->numericAnswer;
    }

    public function setNumericAnswer($numericAnswer) {
        $this->numericAnswer = $numericAnswer;
    }

    public function getDateAnswer($useTimezone = true) {
        return $this->_getDate($this->dateAnswer, $this->getDateAnswerTimeZone(), $useTimezone);
    }

    public function setDateAnswer($dateAnswer) {
        $this->dateAnswer = $dateAnswer;
    }

    public function getDateAnswerTimeZone() {
        return $this->dateAnswerTimeZone;
    }

    public function setDateAnswerTimeZone($dateAnswerTimeZone) {
        $this->dateAnswerTimeZone = $dateAnswerTimeZone;
    }

    public function getTextAnswer() {
        return $this->textAnswer;
    }

    public function setTextAnswer($textAnswer) {
        $this->textAnswer = $textAnswer;
    }

    public function getAnswer($formatted = true) {
        $fields = array("dateAnswer", "numericAnswer", "stringAnswer", "textAnswer");
        foreach ($fields as $field) {
            $value = $this->get($field);
            if (!is_null($value)) {
                if ($field == "dateAnswer")
                    return ($formatted) ? \IOS\Classes\DateFormatter::format($this->getDateAnswer(false), \IOS\Classes\DateFormatter::OUTPUT_DATE) : $this->getDateAnswer(false);
                else if($field == "numericAnswer")
                    return ((abs($value) - intval(abs($value))) > 0) ? floatval($value) : intval($value);
                else
                    return $value;
            }
        }
        return null;
    }

    public function setAnswer($answer, $answerType = "string") {
        $this->reset();
        $wasAnswerArray = false;
        if (is_array($answer)) {
            $answer = implode("|", $answer);
            $wasAnswerArray = true;
        }

        $answer = trim($answer);
        if (strlen($answer)) {
            if ($answerType == "date") {
                $dateObject = \IOS\Classes\Date::getInstance()->convertStringToUTCDate($answer, \IOS\Classes\DateFormatter::INPUT_DATE);
                if ($dateObject instanceof \DateTime)
                    $this->setDateAnswer($dateObject);
                else
                    $this->setStringAnswer($answer);
            }
            else if (!$wasAnswerArray && is_numeric($answer))
                $this->setNumericAnswer($answer);
            else if (strlen($answer) <= 255)
                $this->setStringAnswer($answer);
            else
                $this->setTextAnswer($answer);
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

    public function reset() {
        $fields = array("stringAnswer", "numericAnswer", "textAnswer");

        foreach ($fields as $field)
            $this->set($field, null);

        $this->deleted = false;
    }

    public function set($field, $value) {
        $method = 'set' . ucfirst($field);
        $value = (strlen(trim($value)) > 0) ? $value : null;

        if (!method_exists($this, $method))
            throw new \Exception("Invalid {$field} property");

        $this->$method($value);
    }

    public function get($field) {
        $method = 'get' . ucfirst($field);

        if (!method_exists($this, $method))
            throw new \Exception("Invalid {$field} property");

        return $this->$method();
    }

    public function auditable() {
        return $this->getId();
    }

    public function __toString() {
        return $this->getId();
    }

}