<?php
namespace Validation\Validator;

class Required extends \Phalcon\Validation\Validator\PresenceOf
{
    public function validate($validator, $attribute)
    {
        $secondAttribute = $this->getOption('if');
        if(!is_null($secondAttribute)) {
            $secondAttributeValue = trim($validator->getValue($secondAttribute));
            if(is_null($secondAttributeValue) || strlen($secondAttributeValue) == 0) {
                return true;
            }
        }

        return parent::validate($validator, $attribute);
    }
} 