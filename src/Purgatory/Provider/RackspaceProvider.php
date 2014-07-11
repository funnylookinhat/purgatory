<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider;

use OpenCloud\Rackspace;
use FunnyLookinHat\Purgatory\Purgatory\Provider as ExtendProvider;

class RackspaceProvider extends ExtendProvider {

    private $_client;
    private $_service;
    private $_defaultEndpoint = Rackspace::US_IDENTITY_ENDPOINT;

    public function __construct($config)
    {
    	if( ! isset($config->endpoint) )
    		$config->endpoint = $this->_defaultEndpoint;

    	if( ! isset($config->username) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider config missing 'username'.");

    	if( ! isset($config->apiKey) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider config missing 'apiKey'.");

        if( ! isset($config->region) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider config missing 'region'.");

    	try
    	{
            $this->_client = new Rackspace($config->endpoint, array(
                'username' => $config->username,
                'apiKey'   => $config->apiKey,
            ));

            $this->_service = $this->_client->objectStoreService(null, $config->region);
        }
        catch( \Exception $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider not initiaized: ".$e->getMessage());
        }
    }

    public function getContainers()
    {
        $provider_containers = $this->_service->listContainers();
        
        $containers = array();

        foreach( $provider_containers as $provider_container )
            $containers[] = new \FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider\RackspaceContainer($this, $provider_container);

        return $containers;
    }

    public function listContainers()
    {
        $containers = $this->_service->listContainers();
        
        $containerNames = array();

        foreach( $containers as $container )
            $containerNames[] = $container->getName();

        return $containerNames;
    }

    public function getContainer($name)
    {
        try
        {
            $container = $this->_service->getContainer($name);
            return new \FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider\RackspaceContainer($this, $container);
        }
        catch( \Exception $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerDNEException("Could not fetch container: ".$e->getMessage());
        }
    }

    public function createContainer($name)
    {
        try
        {
            $container = $this->_service->createContainer($name);
            return new Purgatory\Provider\Rackspace\RackspaceContainer($container);
        }
        catch( \Exception $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerException("Could not create container: ".$e->getMessage());
        }
    }
}
