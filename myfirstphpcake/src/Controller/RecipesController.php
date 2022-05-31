<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Utility\Security;
use Firebase\JWT\JWT;


class RecipesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions([
            'login',
            'register',
            ]);
    }

   /**
 * Users Login
 *
 * @return \Cake\Http\Response|null|void
 */
public function login()
{
    $this->request->allowMethod(['get', 'post']);

    $result = $this->Authentication->getResult();
    if ($result->isValid()) {
        $user = $result->getData();
        $payload = [
            'sub' => $user->id,
            'exp' => time() + 60,
        ];
        $json = [
            'token' => JWT::encode($payload, Security::getSalt(), 'HS256'),
        ];
    } else {
        $this->response = $this->response->withStatus(401);
        $json = [];
    }
    $this->set(compact('json'));
    $this->viewBuilder()->setOption('serialize', 'json');
}

/**
 * Register method
 *
 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
 */
public function register()
{
    $user = $this->Recipes->newEmptyEntity();
    $message = null;
    if ($this->getRequest()->is(['post', 'put'])) {
        $user = $this->Recipes->patchEntity($user, $this->request->getData());
        if ($this->Recipes->save($user)) {
            $message = __('The user has been saved.');
        } else {
            $message = __('The user could not be saved. Please, try again.');
        }
    }
    $this->set(compact('recipe', 'message'));
    $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
}
}
