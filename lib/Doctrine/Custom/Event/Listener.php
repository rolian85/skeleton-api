<?php
namespace Doctrine\Custom\Event;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;

class Listener implements EventSubscriber
{
    private static $_creator = null;
    private $_annotationReader = null;

    public function getSubscribedEvents()
    {
        return array(
            'onFlush'
        );
    }

    public static function setCreator(\Entity\User $creator)
    {
        self::$_creator = $creator;
    }

    public static function getCreator()
    {
        return self::$_creator;
    }

    public function onFlush(EventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        $userLoggedIn = self::getCreator();
        foreach ($uow->getScheduledEntityInsertions() AS $entity) {
            $class = $em->getClassMetadata(get_class($entity));
            if ($entity instanceof \Entity\BaseEntity) {
                $entity->setCreator($userLoggedIn);
                $entity->setUpdater($userLoggedIn);
            }
            $this->saveTimeZone($class, $entity, $uow);
            $uow->recomputeSingleEntityChangeSet($class, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() AS $entity) {
            $class = $em->getClassMetadata(get_class($entity));
            if ($entity instanceof \Entity\BaseEntity) {
                $entity->setUpdater($userLoggedIn);
            }
            $this->saveTimeZone($class, $entity, $uow);
            $uow->recomputeSingleEntityChangeSet($class, $entity);
        }
    }

    public function saveTimeZone($class, $entity, $uow)
    {
        $reader = $this->getAnnotationReader();
        $changeSet = $uow->getEntityChangeSet($entity);

        foreach ($changeSet AS $field => $values) {
            $updateTimezone = $reader->getPropertyAnnotation($class->getReflectionProperty($field), "Doctrine\Custom\Annotation\UpdateTimezone");
            if(!is_null($updateTimezone) && !is_null($updateTimezone->field)) {
                $timeZoneFieldName = $updateTimezone->field;
                if ($class->hasField($timeZoneFieldName) && (!isset($changeSet[$timeZoneFieldName]) || is_null($changeSet[$timeZoneFieldName][1]))) {
                    $timezone = null;
                    try {
                        $user = $this->getCreator();
                        if(is_object($user)) {
                            $di = \Phalcon\DI::getDefault();
                            $vessel = $user->getVessel();
                            if(is_object($vessel)) {
                                $vesselTimezoneName = $vessel->getTimezone();
                                $timezone = $di->get('TimezoneService')->findIndexByName($vesselTimezoneName);
                            } else {
                                $timezone = $di->get('ConfigService')->date->timezoneIndex;
                            }
                        }
                    } catch (\Exception $e) {
                        $timezone = null;
                    }
                    $class->getReflectionProperty($timeZoneFieldName)->setValue($entity, $timezone);
                }
            }
        }
    }

    public function getAnnotationReader()
    {
        if (is_null($this->_annotationReader)) {
            $reader = new \Doctrine\Common\Annotations\AnnotationReader();
            $this->_annotationReader = new \Doctrine\Common\Annotations\CachedReader($reader, new \Doctrine\Common\Cache\ArrayCache());
        }
        return $this->_annotationReader;
    }
}