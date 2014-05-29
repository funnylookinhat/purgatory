<?php

namespace FunnyLookinHat\Purgatory\Purgatory;

abstract class Object {

	abstract public function __construct();

	abstract public function getName();

	abstract public function updateData($path);

	abstract public function getUrl();


}