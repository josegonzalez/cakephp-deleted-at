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

}
?>