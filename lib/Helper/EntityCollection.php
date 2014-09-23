<?php
namespace Helper;

class EntityCollection
{
    public static function toArray($entities, $level = null)
    {
        $result = array();
        foreach($entities as $entity) {
            $result[] = (!is_null($level)) ? $entity->toArray($level) : $entity->toArray();
        }
        return $result;
    }
}