<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class IsNumber extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        if (!is_numeric($value)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "The field {$attribute} is not a number";
            }

            $validator->appendMessage(new Message($message, $attribute, 'IsBoolean'));
            return false;
        }

        return true;
    }
} 