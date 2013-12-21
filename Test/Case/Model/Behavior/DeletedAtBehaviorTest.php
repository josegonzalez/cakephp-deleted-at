<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

class DeletedUser extends CakeTestModel {

/**
 * name property
 *
 * @var string
 */
  public $name = 'DeletedUser';

/**
 * useDbConfig property
 *
 * @var array
 */
  public $useDbConfig = 'test';

/**
 * validate property
 *
 * @var array
 */
  public $validate = array('user' => 'notEmpty', 'password' => 'notEmpty');

}

class DeletedAtBehaviorTest extends CakeTestCase {

  public $fixtures = array(
    'plugin.deleted_at.deleted_user', 'core.user'
  );

  public function setUp() {
    parent::setUp();
    $this->DeletedUser = ClassRegistry::init('DeletedUser');
    $this->DeletedUser->useTable = 'deleted_users';
    $this->DeletedUser->Behaviors->load('DeletedAt.DeletedAt');
  }

  public function tearDown() {
    unset($this->DeletedUser);
    parent::tearDown();
  }

  public function testFindDeleted() {
    $records = $this->DeletedUser->find('deleted');
    $this->assertEqual(1, count($records));
  }

  public function testFindNonDeleted() {
    $records = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(2, count($records));
  }

  public function testSoftdelete() {
    $this->DeletedUser->softdelete(1);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(1, count($deleted));
    $this->assertEqual(2, count($nonDeleted));

    $this->DeletedUser->softdelete(2);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(2, count($deleted));
    $this->assertEqual(1, count($nonDeleted));

    $this->DeletedUser->softdelete(3);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(3, count($deleted));
    $this->assertEqual(0, count($nonDeleted));
  }

  public function testUnDelete() {
    $this->DeletedUser->undelete(3);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(1, count($deleted));
    $this->assertEqual(2, count($nonDeleted));

    $this->DeletedUser->undelete(2);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(1, count($deleted));
    $this->assertEqual(2, count($nonDeleted));

    $this->DeletedUser->undelete(1);
    $deleted = $this->DeletedUser->find('deleted');
    $nonDeleted = $this->DeletedUser->find('non_deleted');
    $this->assertEqual(0, count($deleted));
    $this->assertEqual(3, count($nonDeleted));
  }

}