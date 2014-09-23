<?php
namespace Validation\Validator;
use Phalcon\Validation\Validator,
    Phalcon\Validation\ValidatorInterface,
    Phalcon\Validation\Message;

class Entity extends Validator implements ValidatorInterface
{
    public function validate($validator, $attribute)
    {
        $value = $validator->getValue($attribute);
        if(!empty($value)) {
            $entityClass = $this->getOption('entity');
            $em = $this->getOption('em');
            if(!is_object($em)) {
                $di = \Phalcon\DI::getDefault();
                $em = $di->get('em');
            }

            $object = $em->find($entityClass, $value);
            if (!is_object($object)) {
                $message = $this->getOption('message');
                if (!$message) {
                    $message = "The field {$attribute} is not a valid entity id";
                }

                $validator->appendMessage(new Message($message, $attribute, 'Entity'));
                return false;
            }
        }

        return true;
    }
} 