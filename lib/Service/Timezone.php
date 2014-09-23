<?php
namespace Service;

class Timezone extends \Phalcon\DI\Injectable
{
    private $_indexes;
    private $_names;

    public function __construct()
    {
        $key = \Helper\CacheRegistry::KEY_TIMEZONE_INDEXES_DATA;
        if(($cachedEnabled = $this->CacheService->isEnabled())) {
            $this->_indexes = $this->CacheService->fetch($key);
        }

        if(!is_array($this->_indexes)) {
            $file = $this->ConfigService->paths->timezone . 'indexes.php';
            if(!file_exists($file)) {
                throw new \Exception($this->MessageService->get('timezoneDataNotFound'));
            }

            $this->_indexes = require_once $file;
            if($cachedEnabled) {
                $this->CacheService->save($key, $this->_indexes);
            }
        }

        $this->_names = array_flip($this->_indexes);
    }

    public function findNameByIndex($index)
    {
        return isset($this->_indexes[$index]) ? $this->_indexes[$index] : null;
    }

    public function findIndexByName($name)
    {
        return isset($this->_names[$name]) ? $this->_names[$name] : null;
    }

    public function findIndexByOffset($offset)
    {
        $offset = preg_replace('/[^0-9\-]/', '', $offset) * 36;
        $timezoneName = timezone_name_from_abbr(null, $offset, true);
        if($timezoneName === false) {
            $timezoneName = timezone_name_from_abbr(null, $offset, false);
        }
        return $this->findIndexByName($timezoneName);
    }
}