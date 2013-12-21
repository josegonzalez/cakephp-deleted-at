<?php
App::uses('ModelBehavior', 'Model');
class DeletedAtBehavior extends ModelBehavior {

	public $mapMethods = array(
		'/findDeleted/' => 'findDeleted',
		'/findNon_deleted/' => 'findNonDeleted',
	);

	public function setup(Model $model, $config = array()) {
			$model->findMethods['deleted'] = true;
			$model->findMethods['non_deleted'] = true;
	}

	public function findDeleted(Model $model, $functionCall, $state, $query, $results = array()) {
			if ($state == 'before') {
				if (empty($query['conditions'])) {
					$query['conditions'] = array();
				}
				$query['conditions']["{$model->alias}.deleted <>"] = null;
				return $query;
			}
			return $results;
	}

	public function findNonDeleted(Model $model, $functionCall, $state, $query, $results = array()) {
			if ($state == 'before') {
				if (empty($query['conditions'])) {
					$query['conditions'] = array();
				}
				$query['conditions']["{$model->alias}.deleted"] = null;
				return $query;
			}
			return $results;
	}

	public function softdelete(Model $model, $id = null) {
		if ($id) {
			$model->id = $id;
		}

		if (!$model->id) {
			return false;
		}

		$deleteCol = 'deleted';
		if (!$model->hasField($deleteCol)) {
			return false;
		}

		$db = $model->getDataSource();
		$now = time();

		$default = array('formatter' => 'date');
		$colType = array_merge($default, $db->columns[$model->getColumnType($deleteCol)]);

		$time = $now;
		if (array_key_exists('format', $colType)) {
			$time = call_user_func($colType['formatter'], $colType['format']);
		}

		if (!empty($model->whitelist)) {
			$model->whitelist[] = $deleteCol;
		}
		$model->set($deleteCol, $time);
		return $model->saveField($deleteCol, $time);
	}

	public function undelete(Model $model, $id = null) {
		if ($id) {
			$model->id = $id;
		}

		if (!$model->id) {
			return false;
		}

		$deleteCol = 'deleted';
		if (!$model->hasField($deleteCol)) {
			return false;
		}

		$model->set($deleteCol, null);
		return $model->saveField($deleteCol, null);
	}
}
