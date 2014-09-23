<?php
namespace Validation;

class Helper
{
    public static function parse(array $data, array $validators)
    {
        $result = $data;
        foreach($validators as $validator) {
            $name = $validator[0];
            $keys = explode('.', $name);
            if(count($keys) > 1) {
                $value = $data;
                $found = false;
                foreach($keys as $key) {
                    if(isset($value[$key])) {
                        $found = true;
                        $value = $value[$key];
                    } else {
                        $found = false;
                        break;
                    }
                }

                if($found) {
                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }
} 