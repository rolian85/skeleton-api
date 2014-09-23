<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class ArrayItem extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);
        if (!is_array($value)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = "The field {$attribute} is not an array";
            }

            $validator->appendMessage(new Message($message, $attribute, 'ArrayItem'));
            return false;
        }

        $definition = $this->getOption('definition');
        $itemValidation = new \Validation\Base();
        $itemValidation->addFromArray($definition);

        $valid = true;
        foreach($value as $key => $item) {
            $itemValidation->validate(\Validation\Helper::parse($item, $itemValidation->getValidators()));
            $messages = $itemValidation->getMessages();
            if($value && !empty($messages)) {
                $valid = false;
            }

            foreach($messages as $field => $fieldMessages) {
                $field = $attribute . "[{$key}].{$field}";
                foreach($fieldMessages as $fieldMessage) {
                    $validator->appendMessage(new Message($fieldMessage, $field, 'ArrayItem'));
                }
            }
        }
        return $valid;
    }
} 