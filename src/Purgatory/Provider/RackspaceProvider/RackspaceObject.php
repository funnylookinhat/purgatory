<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider;

use OpenCloud\Rackspace;
use FunnyLookinHat\Purgatory\Purgatory\Object as ExtendObject;

class RackspaceObject extends ExtendObject {

    private $_object;

    public function __construct($object)
    {
        $this->_object = $object;
    }

    public function getName()
    {
    	return $this->_object->getName();
    }

    public function updateObject($path)
    {
    	try
    	{
    		$data = fopen($path, 'r+');
    		$this->_object->setContent($data);

            // UPDATE OBJECT ?
            
    		return $this;
    	}
    	catch( Exception $e )
    	{
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not update object: ".$e->getMessage());
    	}
    }

    public function getUrl()
    {
    	return $this->_object->getUrl();
    }

}