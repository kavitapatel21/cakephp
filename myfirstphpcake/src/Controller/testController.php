<?php
namespace App\Controller;
use App\Controller\AppController;
use cake\ORM\TableRegistry;

class testController extends AppController{

    public function index()
    {
        $test_table =  TableRegistry::get('test');
        $query = $test_table->find();
        foreach($query as $record){
            echo $record->name;

        }
        }
}
