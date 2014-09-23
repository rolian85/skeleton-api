<?php
namespace Country\Controller;

class RestController extends \Phalcon\Mvc\Controller
{
    public function findAll()
    {
    	$countries = $this->CountryService->findAll();
        $this->response->json = \Helper\EntityCollection::toArray($countries);
    }
}