<?php
namespace Filter\Filter;

class Boolean
{
    public function filter($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}