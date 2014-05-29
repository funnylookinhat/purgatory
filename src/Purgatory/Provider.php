<?php

namespace FunnyLookinHat\Purgatory\Purgatory;

abstract class Purgatory {

	abstract public function __construct($config);

	abstract public function getContainer($name);

	abstract public function listContainers();

	abstract public function createContainer($name);

}