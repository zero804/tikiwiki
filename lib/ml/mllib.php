<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * MachineLearningLib
 *
 * @uses TikiDb_Bridge
 */
class MachineLearningLib extends TikiDb_Bridge
{
	private $table;

	/**
	 *
	 */
	function __construct()
	{
		$this->table = $this->table('tiki_machine_learning_models');
	}

	function get_models()
	{
		$models = $this->table->fetchAll();
		return array_map([$this, 'deserialize'], $models);
	}

	function get_model($mlmId)
	{
		$model = $this->table->fetchFullRow(['mlmId' => $mlmId]);
		$model = $this->deserialize($model);
		$model['instances'] = $this->hydrate($model['payload']);
		return $model;
	}

	function set_model($mlmId, $data)
	{
		$data = $this->serialize($data);
		return $this->table->insertOrUpdate($data, ['mlmId' => $mlmId]);
	}

	function delete_model($mlmId)
	{
		$this->table->delete(['mlmId' => $mlmId]);
		// TODO: delete trained models from cache
		return true;
	}

	function hydrate($payload)
	{
		$instances = [];
		$payload = json_decode($payload);
		foreach ($payload as $learner) {
			$ref = new ReflectionClass('Rubix\ML\\'.$learner->class);
			try {
				$instance = $ref->newInstanceArgs(array_map(function($arg){ return $arg->value; }, $learner->args));
			} catch (TypeError $e) {
				Feedback::error($e->getMessage());
				$instance = tr('(error instantiating)');
			}
			$instances[] = [
				'learner' => preg_replace('/^[^\\\\]*\\\\/', '', $learner->class),
				'class' => $learner->class,
				'instance' => $instance,
				'serialized_args' => json_encode($learner)
			];
		}
		return $instances;
	}

	protected function serialize($model)
	{
		if (is_array($model['trackerFields'])) {
			$model['trackerFields'] = implode(',', $model['trackerFields']);
		}
		return $model;
	}

	protected function deserialize($model)
	{
		$model['trackerFields'] = explode(',', $model['trackerFields']);
		if (empty($model['payload'])) {
			$model['payload'] = '[]';
		}
		return $model;
	}
}
