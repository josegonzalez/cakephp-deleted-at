<?php
App::uses('ModelBehavior', 'Model');
class DeletedAtBehavior extends ModelBehavior {

/**
 * mapMethods property
 *
 * @var array
 */
	public $mapMethods = array(
		'/findDeleted/' => 'findDeleted',
		'/findNon_deleted/' => 'findNonDeleted',
	);

/**
 * Initiate DeletedAt behavior
 *
 * @param Model $Model using this behavior of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $Model, $config = array()) {
		$Model->findMethods['deleted'] = true;
		$Model->findMethods['non_deleted'] = true;
	}

/**
 * Finds deleted records
 *
 * @param Model $Model using this behavior of model
 * @param string $functionCall original function being called
 * @param string $state Either "before" or "after"
 * @param array $query Query
 * @param array $results Results
 * @return mixed array of results or false if none found
 */
	public function findDeleted(Model $Model, $functionCall, $state, $query, $results = array()) {
		if ($state == 'before') {
			if (empty($query['conditions'])) {
				$query['conditions'] = array();
			}
			$query['conditions']["{$Model->alias}.deleted <>"] = null;
			return $query;
		}
		return $results;
	}

/**
 * Finds non-deleted records
 *
 * @param Model $Model using this behavior of model
 * @param string $functionCall original function being called
 * @param string $state Either "before" or "after"
 * @param array $query Query
 * @param array $results Results
 * @return mixed array of results or false if none found
 */
	public function findNonDeleted(Model $Model, $functionCall, $state, $query, $results = array()) {
		if ($state == 'before') {
			if (empty($query['conditions'])) {
				$query['conditions'] = array();
			}
			$query['conditions']["{$Model->alias}.deleted"] = null;
			return $query;
		}
		return $results;
	}

/**
 * Soft deletes a record
 *
 * @param Model $Model using this behavior of model
 * @param int $id record id
 * @return bool
 */
	public function softdelete(Model $Model, $id = null) {
		if ($id) {
			$Model->id = $id;
		}

		if (!$Model->id) {
			return false;
		}

		$deleteCol = 'deleted';
		if (!$Model->hasField($deleteCol)) {
			return false;
		}

		$db = $Model->getDataSource();
		$now = time();

		$default = array('formatter' => 'date');
		$colType = array_merge($default, $db->columns[$Model->getColumnType($deleteCol)]);

		$time = $now;
		if (array_key_exists('format', $colType)) {
			$time = call_user_func($colType['formatter'], $colType['format']);
		}

		if (!empty($Model->whitelist)) {
			$Model->whitelist[] = $deleteCol;
		}
		$Model->set($deleteCol, $time);
		return $Model->saveField($deleteCol, $time);
	}

/**
 *  Undeletes a record
 *
 * @param Model $Model using this behavior of model
 * @param int $id record id
 * @return bool
 */
	public function undelete(Model $Model, $id = null) {
		if ($id) {
			$Model->id = $id;
		}

		if (!$Model->id) {
			return false;
		}

		$deleteCol = 'deleted';
		if (!$Model->hasField($deleteCol)) {
			return false;
		}

		$Model->set($deleteCol, null);
		return $Model->saveField($deleteCol, null);
	}
}
