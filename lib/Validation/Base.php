<?php
namespace Validation;

class Base extends \Phalcon\Validation
{
    public function addFromArray(array $config)
    {
        $standardNamespace = '\Phalcon\Validation\Validator';
        foreach($config as $field => $validators) {
            foreach($validators as $key => $value) {
                if(is_numeric($key)) {
                    $class = ($value[0] != '\\') ? $standardNamespace . '\\' . $value : $value;
                    $this->add($field, new $class());
                } else {
                    $class = ($key[0] != '\\') ? $standardNamespace . '\\' . $key : $key;
                    $this->add($field, new $class($value));
                }
            }
        }
    }

    public function getMessages()
    {
        $messages = array();
        foreach(parent::getMessages() as $message) {
            $content = $message->getMessage();

            // @TODO - Implement translation
            switch ($message->getType()) {
                case 'PresenceOf':
                    $content = 'Value is required and can\'t be empty';
                    break;
            }

            if(!isset($messages[$message->getField()])) {
                $messages[$message->getField()] = array();
            }
            $messages[$message->getField()][] = $content;
        }
        return $messages;
    }
}