<?php

namespace FunnyLookinHat\Purgatory\Purgatory;

abstract class Container {

    abstract public function __construct($container);

    abstract public function getName();

    abstract public function getObject($name);

    abstract public function createObject($name, $path);

    abstract public function getAllObjects();

    abstract public function listAllObjects();

    abstract public function enableCdn();

    abstract public function disableCdn();

    abstract public function getUrl();

    abstract public function getSslUrl();

}