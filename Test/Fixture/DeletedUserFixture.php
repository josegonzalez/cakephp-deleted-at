<?php
App::uses('CakeTestFixture', 'TestSuite/Fixture');
class DeletedUserFixture extends CakeTestFixture {

	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'user' => array('type' => 'string', 'null' => true),
		'password' => array('type' => 'string', 'null' => true),
		'created' => 'datetime',
		'updated' => 'datetime',
		'deleted' => array('type' => 'datetime', 'null' => true),
	);

	public $records = array(
		array('user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31', 'deleted' => '2007-03-18 10:45:31'),
		array('user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31', 'deleted' => null),
		array('user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31', 'deleted' => null),
	);

}
