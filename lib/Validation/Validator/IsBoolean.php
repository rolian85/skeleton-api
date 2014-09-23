<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class IsBoolean extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        if (!is_bool($value)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "The field {$attribute} is not a boolean";
            }

            $validator->appendMessage(new Message($message, $attribute, 'IsBoolean'));
            return false;
        }

        return true;
    }
} 