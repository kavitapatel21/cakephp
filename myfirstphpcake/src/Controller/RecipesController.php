<?php

namespace App\Controller;

use Cake\ORM\Table;
use Cake\Http\ServerRequest;
use Cake\Event\EventInterface;
use Cake\Datasource\FactoryLocator;

class RecipesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        //$this->loadComponent("Auth");
        //$this->blogObject = $this->getTableLocator()->get('Recipe'); // Loading Recipe Class
    }

    public function index()
    {
        $recipes = $this->Recipes->find('all')->all();
        $this->set('recipes', $recipes);
        $this->viewBuilder()->setOption('serialize', ['recipes']);
    }

    public function view($id)
    {
        $recipe = $this->Recipes->get($id);
        $this->set('recipe', $recipe);
        $this->viewBuilder()->setOption('serialize', ['recipe']);
    }

    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $recipe = $this->Recipes->newEntity($this->request->getData(), ['validate' => false]);
        $name=$recipe['name'];
        $email=$recipe['email'];
        $pw=$recipe['password'];
        //$result = $this->Recipes->save($recipe);
        $email = $recipe->email;
        $user = $this->Recipes->find('all')
            ->where([
                'Recipes.email' => $email,
            ])
            ->first();
        if ($user) {
            $message = 'email already exist';
        } else{
            if(!empty($name) && !empty($email) && !empty($pw) ){
            $result = $this->Recipes->save($recipe);
            $message = 'inserted';
        }
        }
        $this->set([
            'message' => $message,
            'recipe' => $recipe,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
    }

    public function edit($id)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $recipe = $this->Recipes->get($id);
        $recipe = $this->Recipes->patchEntity($recipe, $this->request->getData(), ['validate' => false]);
        if ($this->Recipes->save($recipe)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' => $recipe,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $recipe = $this->Recipes->get($id);
        $message = 'Deleted';
        if (!$this->Recipes->delete($recipe)) {
            $message = 'Error';
        }
        $this->set('message', $message);
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    public function login()
    {
        $data = $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            return $this->redirect(['controller' => 'Recipes', 'action' => 'index']);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid Email or Password'));
        }
    }
}
