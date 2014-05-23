<?php

namespace FunnyLookinHat;

class PurgatoryException extends Exception {}
class PurgatoryContainerException extends Exception {}
class PurgatoryContainerDNEException extends Exception {}
class PurgatoryContainerExistsException extends Exception {}
class PurgatoryFileException extends Exception {}
class PurgatoryFileDNEException extends Exception {}
class PurgatoryFileExistsException extends Exception {}

class Purgatory {

	private $_config;
	private $_provider;

	public function __construct($config)
	{
		// Initialize Provider
	}

	public function getContainer($name)
	{
		return $this->_provider->getContainer($name);
	}

	public function createContainer()
	{
		return $this->_provider->createContainer($name);
	}
}