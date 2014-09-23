<?php
namespace User\Controller;

class RestController extends \Phalcon\Mvc\Controller
{
    public function get($id)
    {
        $user = $this->UserService->findById($id);
        if(!is_object($user)) {
            throw new \Exception\EntityNotFoundException();
        }

        $this->response->json = $user->toArray(\Entity\User::HYDRATION_LEVEL_ADVANCED);
    }

    public function findByUsername($username)
    {
        $user = $this->UserService->findOneByUsername($username);
        if(!is_object($user)) {
            throw new \Exception\EntityNotFoundException();
        }

        $this->response->json = $user->toArray(\Entity\User::HYDRATION_LEVEL_BASIC);
    }

    public function search()
    {
        $data = [
            'namse' => '',
        ];

        $validation = new \User\Validation\Create();
        $validation->validate($data);
        $messages = $validation->getMessages();

        // This is how to get the user logged in
        $user = $this->UserService->getCurrentUser();

        if(count($messages)) {
            throw new \Exception\ValidationException($messages);
        }

        $this->response->json = array('HERE');
    }

    public function create()
    {
        echo json_encode(array('message' => 'Create!'));
    }

    public function update($id)
    {
        echo json_encode(array('message' => 'Update! ' . $id));
    }
	
    public function delete($id)
    {
        echo json_encode(array('message' => 'Delete! ' . $id));
    }
}