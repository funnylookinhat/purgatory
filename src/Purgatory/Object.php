<?php

namespace FunnyLookinHat\Purgatory\Purgatory;

abstract class Object {

	abstract public function __construct($container, $object);

	abstract public function getName();

	abstract public function update($path);

	abstract public function getChecksum();

    abstract public function getUrl();

    abstract public function getSslUrl();

}