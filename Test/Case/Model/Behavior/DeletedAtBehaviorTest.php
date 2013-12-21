<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
require_once CAKE . 'Test' . DS . 'CASE' . DS . 'Model' . DS . 'models.php';
class DeletedAtBehaviorTest extends CakeTestCase {

  public $fixtures = array(
    'plugin.deleted_at.deleted_user', 'core.user'
  );

  public function setUp() {
    parent::setUp();
    $this->DeletedUser = ClassRegistry::init('User');
    $this->DeletedUser->useTable = 'deleted_users';
    $this->DeletedUser->Behaviors->load('DeletedAt.DeletedAt');
  }

  public function tearDown() {
    unset($this->DeletedUser);
    parent::tearDown();
  }

}