<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider;

use OpenCloud\Rackspace;
use FunnyLookinHat\Purgatory\Purgatory\Container as ExtendContainer;

class RackspaceContainer extends ExtendContainer {
    
    private $_container;

    public function __construct($container)
    {
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
        	$file = $this->_container->getObject($name);
        	return new FunnyLookinHat\Purgatory\Provider\RackspaceProvider\File($file);
        }
        catch( Exception $e )
        {
        	throw new PurgatoryFileDNEException("File does not exist: ".$e->getMessage());
        }
    }

    public function createObject($name, $path)
    {
        try
        {
        	$data = fopen($path, 'r+');
        	$file = $this->_container->uploadObject($name, $data);
        	return new FunnyLookinHat\Purgatory\Provider\RackspaceProvider\File($file);
        }
        catch( Exception $e )
        {
        	throw new PurgatoryFileException("Could not upload file: ".$e->getMessage());
        }
    }

}