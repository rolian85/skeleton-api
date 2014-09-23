<?php
namespace Exception;

class PhpErrorException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }
}