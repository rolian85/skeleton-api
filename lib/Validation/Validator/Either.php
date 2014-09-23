<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class Either extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $attributes = $this->getOption('attributes');
        if(!is_array($attributes)) $attributes = array();
        $attributes[] = $attribute;

        $emptyCount = 0;
        foreach($attributes as $attribute) {
            $value = trim($validator->getValue($attribute));
            if(strlen($value) == 0) {
                $emptyCount++;
            }
        }

        if($emptyCount == count($attributes)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "At least one of the following fields is required: " . implode(", ", $attributes);
            }

            $validator->appendMessage(new Message($message, $attribute, 'IsBoolean'));
            return false;
        }

        return true;
    }
} 