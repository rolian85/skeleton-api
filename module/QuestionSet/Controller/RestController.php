<?php
namespace QuestionSet\Controller;

class RestController extends \Phalcon\Mvc\Controller
{

    public function findByGroup($groupKey)
    {
        $questionSets = $this->QuestionSetService->findByGroup($groupKey);
        $this->response->json = \Helper\EntityCollection::toArray($questionSets, \Entity\QuestionSet::HYDRATION_LEVEL_ADVANCED);
    }

    public function findOneByNameKey($nameKey)
    {
        $questionSet = $this->QuestionSetService->findOneByNameKey($nameKey);
        if(!is_object($questionSet)) {
            throw new \Exception\EntityNotFoundException();
        }

        $this->response->json = $questionSet->toArray(\Entity\QuestionSet::HYDRATION_LEVEL_ADVANCED);
    }

    public function findAll()
    {
        $questionSets = $this->QuestionSetService->findAll();
        $this->response->json = \Helper\EntityCollection::toArray($questionSets, \Entity\QuestionSet::HYDRATION_LEVEL_ADVANCED);
    }
}