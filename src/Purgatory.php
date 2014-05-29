<?php

namespace FunnyLookinHat\Purgatory;

class PurgatoryException extends \Exception {}
class PurgatoryContainerException extends \Exception {}
class PurgatoryContainerDNEException extends \Exception {}
class PurgatoryContainerExistsException extends \Exception {}
class PurgatoryFileException extends \Exception {}
class PurgatoryFileDNEException extends \Exception {}
class PurgatoryFileExistsException extends \Exception {}

class Purgatory {

	private $_config;
	private $_provider;

	private $_supportedProviders = array(
		'Rackspace',
	);

	public function __construct($config)
	{
		if( ! isset($config->provider) )
			throw new PurgatoryException("Invalid provider - not set.");

		if( ! in_array(ucwords($config->provider), $this->_supportedProviders) )
			throw new PurgatoryException("Invalid provider - does not exist: ".$config->provider);

		$providerClass = '\\FunnyLookinHat\\Purgatory\\Purgatory\\Provider\\'.ucwords($config->provider).'Provider';
		$this->_provider = new $providerClass($config);
	}

	public function getContainer($name)
	{
		return $this->_provider->getContainer($name);
	}

	public function listContainers()
	{
		return $this->_provider->listContainers();
	}

	public function createContainer($name)
	{
		return $this->_provider->createContainer($name);
	}
}