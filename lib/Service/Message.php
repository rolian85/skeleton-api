<?php
namespace Service;

class Message extends \Phalcon\DI\Injectable
{
    private $_messages;

    public function __construct()
    {
        $lang = $this->ConfigService->intl->lang;
        $langKey = \Helper\CacheRegistry::KEY_INTL_LANG . $lang;
        if(($cachedEnabled = $this->CacheService->isEnabled())) {
            $this->_messages = $this->CacheService->fetch($langKey);
        }

        if(!is_array($this->_messages)) {
            $file = $this->ConfigService->paths->lang . $lang . '.php';
            if(!file_exists($file)) {
                throw new \Exception("Language {$lang} not supported");
            }

            $this->_messages = require_once $file;
            if($cachedEnabled) {
                $this->CacheService->save($langKey, $this->_messages);
            }
        }
    }

    public function get($key)
    {
        return isset($this->_messages[$key]) ? $this->_messages[$key] : null;
    }
}