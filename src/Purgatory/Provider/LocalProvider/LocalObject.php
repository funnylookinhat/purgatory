<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider;

use FunnyLookinHat\Purgatory\Purgatory\Object as ExtendObject;

class LocalObject extends ExtendObject {

    private $_object;
    private $_container;

    public function __construct($container, $object)
    {
        $this->_container = $container;
        $this->_object = $object;
    }

    public function getName()
    {
    	return $this->_object->filename;
    }

    public function update($path)
    {
    	$new_object = $this->_container->updateObject($this->_object->filename, $path);

    	$this->_object->checksum = $new_object->getChecksum();
    	$this->_object->filesize = $new_object->getFilesize();

    	return $this;
    }

    public function getFilesize()
    {
    	return $this->_object->filesize;
    }

    public function getChecksum()
    {
        return $this->_object->checksum;
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
        $this->_container->deleteObject($this->_object->filename);
    }

}