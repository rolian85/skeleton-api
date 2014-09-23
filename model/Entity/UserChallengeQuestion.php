<?php

namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity @ORM\Table(name="user_challenge_question")
 */
class UserChallengeQuestion extends BaseEntity implements PersistableBase, Auditable {

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="challengeQuestions")
     */
    protected $user;

    /**
     * @ORM\Column(type="string",length=40)
     */

    /**
     * @ORM\ManyToOne(targetEntity="ChallengeQuestion", inversedBy="users")
     */
    protected $challengeQuestion;

    /** @ORM\Column(length=100) */
    protected $answer;

    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setChallengeQuestion($challengeQuestion) {
        $this->challengeQuestion = $challengeQuestion;
    }

    public function getChallengeQuestion() {
        return $this->challengeQuestion;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
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

}
