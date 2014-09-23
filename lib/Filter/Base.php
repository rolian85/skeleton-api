<?php
namespace Filter;

class Base
{
    protected $_filters;
    protected $_phalconFilter;

    public function setFilters($filters)
    {
        $this->_filters = $filters;
    }

    public function getFilters()
    {
        return $this->_filters;
    }

    public function getPhalconFilter()
    {
        if(is_null($this->_phalconFilter)) {
            $this->_phalconFilter = new \Phalcon\Filter();
        }

        return $this->_phalconFilter;
    }

    public function sanitize(array $data, $filters = null)
    {
        $phalconFilter = $this->getPhalconFilter();
        $result = $data;

        $definition = (is_array($filters)) ? $filters : $this->getFilters();
        foreach($definition as $field => $filters) {
            if(isset($result[$field])) {
                foreach($filters as $filter) {
                    if(is_array($filter)) {
                        foreach($result[$field] as $key => $item) {
                            $result[$field][$key] = $this->sanitize($item, $filter);
                        }
                    } else {
                        if($filter[0] == '\\') {
                            $phalconFilter->add($filter, new $filter());
                        }
                        $result[$field] = $phalconFilter->sanitize($result[$field], $filter);
                    }
                }
            }
        }

        return $result;
    }
}