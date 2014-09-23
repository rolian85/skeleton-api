<?php
namespace Exception;

class ValidationException extends \Exception
{
    private $_messages;

    public function __construct(array $messages, $message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_messages = $messages;
    }

    public function getMessages()
    {
        return $this->_messages;
    }
}