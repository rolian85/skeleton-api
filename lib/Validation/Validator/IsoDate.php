<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class IsoDate extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);

        if (!empty($value) && !preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})[\+\-](\d{4})$/', $value)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "The field {$attribute} is not a valid ISO8601 date";
            }

            $validator->appendMessage(new Message($message, $attribute, 'IsoDate'));
            return false;
        }

        return true;
    }
} 