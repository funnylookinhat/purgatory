<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider;

use FunnyLookinHat\Purgatory\Purgatory\Container as ExtendContainer;

class LocalContainer extends ExtendContainer {
    
    private $_container;
    private $_data;
    private $_data_file = ".data.json";

    /**
     * JSON Data Schema ( Container )
     * {
     *   "objects": {
     *     "someFile.png": {
     *       "checksum": "yx846hm9l3ohkj5mj7yiiv4ygqc8djrh",
     *       "filesize": 79725,
     *       "filename": "someFile.png"
     *     },
     *     "anotherFile.png": {
     *       "checksum": "nv832nv8992ovwni20923093i3imvddk",
     *       "filesize": 31741,
     *       "filename": "anotherFile.png"
     *     }
     *   },
     *   "index": [
     *     "someFile.png",
     *     "anotherFile.png"
     *   ]
     * }
     */

    public function __construct($container)
    {
        $this->_container = $container;

        try
        {
	        if( ! file_exists($this->_container->path.'/'.$this->_data_file) )
	        {
	            $this->_data = (object)array(
                    'index' => array(),
	            	'objects' => (object)array(),
	            );

	            file_put_contents($this->_container->path.'/'.$this->_data_file, json_encode($this->_data));
	        }
	        else
	        {
	        	$this->_data = json_decode(file_get_contents($this->_container->path.'/'.$this->_data_file));
	        }
	    }
	    catch( \Exception $e )
	    {
	    	throw new \FunnyLookinHat\Purgatory\PurgatoryException("Could not load or initialize data storage for container.  Are write permissions setup correctly?");
	    }
    }

    public function getName()
    {
    	return $this->_container->name;
    }

    public function getObject($name)
    {
    	if( ! isset($this->_data->objects->{$name}) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectDNEException("File not found in index: ".$name.".");
        
    	return new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalObject($this, $this->_data->objects->{$name});
    }

    public function createObject($name, $path)
    {
    	if( isset($this->_data->objects->{$name}) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectExistsException("Object already exists: ".$name.".");

    	try
    	{
    		copy($path, $this->_container->path.'/'.$name);
    	}
    	catch( Exception $e )
    	{
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not upload object: ".$e->getMessage());
    	}

    	$object = (object)array(
			'filename' => $name,
			'filesize' => filesize($this->_container->path.'/'.$name),
			'checksum' => md5_file($this->_container->path.'/'.$name),
		);

		$this->_data->objects->{$name} = $object;
        $this->_data->index[] = $name;

        file_put_contents($this->_container->path.'/'.$this->_data_file, json_encode($this->_data));

        return new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalObject($this, $object);
    }

    // This is primarily called by LocalObject - sort of a weird use case.
    public function updateObject($name, $path)
    {
    	if( ! isset($this->_data->objects->{$name}) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectDNEException("File not found in index: ".$name.".");

    	try
    	{
    		copy($path, $this->_container->path.'/'.$name);
    	}
    	catch( Exception $e )
    	{
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not upload object: ".$e->getMessage());
    	}

    	$object = (object)array(
			'filename' => $name,
			'filesize' => filesize($this->_container->path.'/'.$name),
			'checksum' => md5_file($this->_container->path.'/'.$name),
		);

		$this->_data->objects->{$name} = $object;

		file_put_contents($this->_container->path.'/'.$this->_data_file, json_encode($this->_data));

		return new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalObject($this, $object);
    }

    // Another function primarily used by LocalObject - not extended from base object
    public function deleteObject($name)
    {
    	if( ! isset($this->_data->objects->{$name}) )
    		throw new \FunnyLookinHat\Purgatory\PurgatoryObjectDNEException("File not found in index: ".$name.".");

    	try
        {
        	unlink($this->_container->path.'/'.$name);
        }
        catch( Exception $e )
        {
        	throw new \FunnyLookinHat\Purgatory\PurgatoryObjectException("Could not delete object: ".$e->getMessage());
        }

        array_splice($this->_container->index, array_search($name, $this->_container->index), 1);
        unset($this->_container->objects->{$name});

        file_put_contents($this->_container->path.'/'.$this->_data_file, json_encode($this->_data));
    }

    public function getAllObjects()
    {
        $objects = array();

        foreach( $this->_data->index as $name )
        	$objects[] = new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalObject($this, $this->_data->objects->{$name});

        return $objects;
    }

    public function listAllObjects()
    {
        return $this->_data->index;
    }

    public function enableCdn()
    {
        // CDN Always Enabled
    }

    public function disableCdn()
    {
        throw new \FunnyLookinHat\Purgatory\PurgatoryException("Cannot disable access on a local provider.");
    }

    public function getUrl()
    {
        return $this->_container->url;
    }

    public function getSslUrl()
    {
        return $this->_container->url;
    }

    

}