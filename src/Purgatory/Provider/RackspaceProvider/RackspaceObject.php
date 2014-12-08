<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\RackspaceProvider;

use OpenCloud\Rackspace;
use FunnyLookinHat\Purgatory\Purgatory\Object as ExtendObject;

class RackspaceObject extends ExtendObject {

    private $_object;
    private $_container;

    public function __construct($container, $object)
    {
        $this->_container = $container;
        $this->_object = $object;
    }

    public function getName()
    {
        return $this->_object->getName();
    }

    public function update($path)
    {
        try
        {
            $data = fopen($path, 'r+');
            $this->_object->setContent($data);
            $this->_object->update();

            return $this;
        }
        catch( Exception $e )
        {
            throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not update object: ".$e->getMessage());
        }
    }

    public function getFilesize()
    {
        return $this->_object->getContentLength();
    }

    public function getChecksum()
    {
        return $this->_object->getEtag();
    }

    public function getUrl()
    {
        return $this->_container->getUrl().'/'.$this->getName();
    }

    public function getSslUrl()
    {
        return $this->_container->getSslUrl().'/'.$this->getName();
    }

    public function delete()
    {
        $this->_object->delete();
    }

}