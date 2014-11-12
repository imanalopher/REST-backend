<?php

use Phinx\Migration\AbstractMigration;

class AddUserTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('users');
              $users->addColumn('username', 'string', array('limit' => 20))
              ->addColumn('email', 'string', array('limit' => 20))
              ->addColumn('password', 'string', array('limit' => 40))
              ->addColumn('first_name', 'string', array('limit' => 30, 'default' => null))
              ->addColumn('last_name', 'string', array('limit' => 30, 'default' => null))
              ->addColumn('auth_token', 'string', array('null'=>true, 'limit' => 100, 'default' => null))
              ->addColumn('salt', 'string', array('limit' => 100, 'default' => null))
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime', array('default' => null))
              ->addIndex(array('username', 'email'), array('unique' => true))
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('users');

    }
}