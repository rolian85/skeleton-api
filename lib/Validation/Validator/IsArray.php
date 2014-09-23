<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class IsArray extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        if (!is_array($value)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "The field {$attribute} is not an array";
            }

            $validator->appendMessage(new Message($message, $attribute, 'IsArray'));
            return false;
        }

        return true;
    }
} 