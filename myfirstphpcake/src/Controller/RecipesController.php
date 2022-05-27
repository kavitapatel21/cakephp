<?php

namespace App\Controller;

use Cake\ORM\Table;
use Cake\Http\ServerRequest;

class RecipesController extends AppController
{
    private $blogObject;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent("Auth");
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
        $recipe = $this->Recipes->newEntity($this->request->getData(),['validate' => false]);
       
        $result = $this->Recipes->save($recipe);
        if ($result) {
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
        //$this->request->allowMethod(['post']);
        $this->request->params['recipe'];
        $result = $this->Authentication->getResult();
        if ($result) {
            $message = 'user login';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' =>$result,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
        

       /**$response = [
            'result' => false,
            'code' => 'access-denied',
            'message' => 'Invalid credentials or access denied.'
        ];
        $this->set(compact('loggedIn', 'response'));
        $this->set('_serialize', ['loggedIn', 'response']);*/
    }
}
