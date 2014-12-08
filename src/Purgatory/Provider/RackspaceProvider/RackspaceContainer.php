<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider;

use OpenCloud\Rackspace;
use FunnyLookinHat\Purgatory\Purgatory\Container as ExtendContainer;

class RackspaceContainer extends ExtendContainer {
    
    private $_provider;
    private $_container;

    public function __construct($provider, $container)
    {
        $this->_provider = $provider;
        $this->_container = $container;
    }

    public function getName()
    {
        return $this->_container->getName();
    }

    public function getObject($name)
    {
        try
        {
            $object = $this->_container->getObject($name);
            return new \FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider\RackspaceObject($this, $object);
        }
        catch( \OpenCloud\ObjectStore\Exception\ObjectNotFoundException $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryObjectDNEException("File does not exist: ".$e->getMessage());
        }
    }

    public function createObject($name, $path)
    {
        try
        {
            $object = $this->getObject($name);

            throw new \FunnyLookinHat\Purgatory\PurgatoryObjectExistsException("Object already exists: ".$name);
        }
        catch( \FunnyLookinHat\Purgatory\PurgatoryObjectDNEException $ode )
        {
            // File does not exist - we're good to go.
        }

        try
        {
            $data = fopen($path, 'r+');
            $object = $this->_container->uploadObject($name, $data);
            return new \FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider\RackspaceObject($this, $object);
        }
        catch( Exception $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not upload object: ".$e->getMessage());
        }
    }

    public function getAllObjects()
    {
        $objects = array();

        $container_objects = $this->_container->objectList();

        while( count($container_objects) )
        {
            foreach( $container_objects as $container_object )
                $objects[] = new \FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider\RackspaceObject($this, $container_object);
            
            $container_objects = $this->_container->objectList(array(
                'marker' => end($objects)->getName(),
            ));
        }

        return $objects;
    }

    public function listAllObjects()
    {
        $objects = array();

        $container_objects = $this->_container->objectList();

        while( count($container_objects) )
        {
            foreach( $container_objects as $container_object )
                $objects[] = $container_object->getName();
            
            $container_objects = $this->_container->objectList(array(
                'marker' => end($objects),
            ));
        }

        return $objects;
    }

    public function enableCdn()
    {
        $this->_container->enableCdn();
    }

    public function disableCdn()
    {
        $this->_container->disableCdn();
    }

    public function getUrl()
    {
        try
        {
            $this->_container->getCdn()->isCdnEnabled();
        }
        catch( \OpenCloud\Common\Exceptions\CdnNotAvailableError $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerCDNDisabledException("CDN is not enabled.");
        }

        return $this->_container->getCdn()->getCdnUri();
    }

    public function getSslUrl()
    {
        try
        {
            $this->_container->getCdn()->isCdnEnabled();
        }
        catch( \OpenCloud\Common\Exceptions\CdnNotAvailableError $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerCDNDisabledException("CDN is not enabled.");
        }
        
        return $this->_container->getCdn()->getCdnSslUri();
    }

    

}