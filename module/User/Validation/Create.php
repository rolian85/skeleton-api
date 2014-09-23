<?php
namespace User\Validation;

class Create extends \Validation\Base
{
    public function initialize()
    {
        $this->addFromArray(array(
            'name' => array(
                'filters' => array('trim'),
                'validators' => array(
                    'PresenceOf',
                    'Regex' => array('pattern' => '/^[0-9]{4}[-\/](0[1-9]|1[12])[-\/](0[1-9]|[12][0-9]|3[01])$/')
                )
            )
        ));
    }
}