<?php

namespace FunnyLookinHat\Purgatory\Purgatory;

abstract class Provider {

	abstract public function __construct($config);

	abstract public function getContainer($name);

	abstract public function getContainers();

	abstract public function listContainers();

	abstract public function createContainer($name);

}