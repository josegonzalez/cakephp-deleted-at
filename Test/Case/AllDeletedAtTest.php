<?php
/**
 * All DeletedAt plugin tests
 */
class AllDeletedAtTest extends CakeTestCase {

/**
 * Suite define the tests for this plugin
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All DeletedAt test');

		$path = CakePlugin::path('DeletedAt') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
