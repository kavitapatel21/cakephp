<?php
declare(strict_types=1);

use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;
use Migrations\AbstractSeed;

/**
 * Database seed.
 */
class DatabaseSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $hasher = new DefaultPasswordHasher();
        $users = [
            [
                'name' => 'two',
                'password' => $hasher->hash('secret'),
                'created' => FrozenTime::now(),
                'modified' => FrozenTime::now(),
            ]
        ];

        $table = $this->table('Recipes');
        $table->insert($users)->save();
    }
}