<?php
namespace Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\MappedSuperclass
 */
class BaseEntity
{
    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @UpdateTimezone(field="createdTimeZone")
     */
    protected $created;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $createdTimeZone;

    /**
     * @var User $creator
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $creator;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @UpdateTimezone(field="updatedTimeZone")
     */
    protected $updated;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $updatedTimeZone;

    /**
     * @var User $updater
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $updater;

    /**
     * @var \DateTime $deleted
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted;

    /**
     * Internal method to convert a date from UTC
     * to any desired timezone given the index
     * of a timezone
     *
     * @param $date
     * @param null $timezone
     * @param bool $convertTimeZone
     * @param bool $isDateTime
     * @param bool $ignoreDst
     * @return \DateTime|null
     */
    protected function _getDate ($date, $timezone = null, $convertTimeZone = true, $isDateTime = true)
    {
        if ($date instanceof \DateTime) {
            $date = clone $date;
            if ($convertTimeZone) {
                try {
                    $clonedDate = clone $date;
                    $di = \Phalcon\DI::getDefault();
                    $timezoneString = $di->get('TimezoneService')->findNameByIndex($timezone);

                    if(is_string($timezoneString)) {
                        $timezone = new \DateTimeZone($timezoneString);
                        $clonedDate->setTimezone($timezone);
                    }

                    if(!$isDateTime)
                        $clonedDate->setDate($date->format("Y"), $date->format("m"), $date->format("d"));

                    return $clonedDate;
                } catch (\InvalidArgumentException $e) {
                    return $date;
                }
            } else {
                return $date;
            }
        }

        return null;
    }

    public function getCreated($useTimezone = true)
    {
        return $this->_getDate($this->created, $this->getCreatedTimeZone(), $useTimezone);
    }

    public function getCreatedTimeZone()
    {
        return $this->createdTimeZone;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getUpdated($useTimezone = true)
    {
        return $this->_getDate($this->updated, $this->getUpdatedTimeZone(), $useTimezone);
    }

    public function getUpdatedTimeZone()
    {
        return $this->updatedTimeZone;
    }

    public function getUpdater()
    {
        return $this->updater;
    }

    public function setUpdater($updater)
    {
        $this->updater = $updater;
    }

    public function isDeleted()
    {
        return !is_null($this->deleted);
    }

    public function delete()
    {
        $this->deleted = new \DateTime();
    }

    public function restore()
    {
        $this->deleted = null;
    }

    public function getDeleter()
    {
        if ($this->isDeleted())
            return $this->getUpdater();

        return null;
    }

    protected function _formatDate($date)
    {
        if(!is_object($date) || !($date instanceof \DateTime))
            return null;

        $di = \Phalcon\DI::getDefault();
        return $date->format($di->get('ConfigService')->date->format);
    }

    protected function _getDateFromFormat($value, $convertToUTC = true)
    {
        $date = null;
        if(!empty($value)) {
            try {
                $di = \Phalcon\DI::getDefault();
                $date = \DateTime::createFromFormat($di->get('ConfigService')->date->format, $value);
                if($convertToUTC) {
                    $date->setTimezone(new \DateTimeZone('UTC'));
                }
            } catch(\Exception $e) {
                $date = null;
            }
        }
        return (is_object($date)) ? $date : null;
    }

    protected function _getTimezoneFromFormat($value)
    {
        $date = $this->_getDateFromFormat($value, false);
        if(is_object($date) && $date instanceof \DateTime) {
            $timezone = trim($date->getTimezone()->getName());
            $di = \Phalcon\DI::getDefault();
            $timezoneService = $di->get('TimezoneService');
            if(preg_match('/^[\+\-][0-9]{2}:[0-9]{2}$/', $timezone)) {
                return $timezoneService->findIndexByOffset($timezone);
            } else {
                return $timezoneService->findIndexByName($timezone);
            }
        }

        return null;
    }

    protected function objectToArray($object)
    {
        return (is_object($object)) ? $object->toArray() : null;
    }
}