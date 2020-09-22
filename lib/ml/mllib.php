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
			$instances[] = $this->hydrate_single($learner->class, $learner->args);
		}
		return $instances;
	}

	function hydrate_single($class, $args)
	{
		if (empty($class)) {
			return [
				'learner' => null,
				'class' => null,
				'instance' => null,
				'serialized_args' => null
			];
		}
		$ref = new ReflectionClass('Rubix\ML\\'.$class);
		$instance_args = [];
		if ($args) {
			foreach ($args as $arg)
				if ($arg->input_type == 'rubix') {
					$iargs = $arg->value;
					$instance = $this->hydrate_single($iargs->class, $iargs->args);
					$instance_args[] = $instance['instance'];
				} else {
					$instance_args[] = $arg->value;
				}
		}
		try {
			$instance = $ref->newInstanceArgs($instance_args);
		} catch (TypeError $e) {
			Feedback::error(tr('Error instantiating %0 with arguments %1: %2', $class, print_r($instance_args, 1), $e->getMessage()));
			$instance = tr('(error instantiating)');
		}
		return [
			'learner' => preg_replace('/^[^\\\\]*\\\\/', '', $class),
			'class' => $class,
			'instance' => $instance,
			'serialized_args' => json_encode(['class' => $class, 'args' => $args])
		];
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
