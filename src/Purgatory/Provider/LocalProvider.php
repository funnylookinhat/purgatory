<?php

namespace FunnyLookinHat\Purgatory\Purgatory\Provider;

use FunnyLookinHat\Purgatory\Purgatory\Provider as ExtendProvider;

class LocalProvider extends ExtendProvider {

    private $_storage;
    private $_base_url;
    private $_data;
    private $_data_file = ".data.json";

    /**
     * JSON Data Schema ( Provider )
     * {
     *   "index": [
     *     "containerName",
     *     "anotherContainer"
     *   ],
     *   "containers": {
     *     "containerName": {
     *       "path": "/full/path/to/folder",
     *       "name": "containerName",
     *       "url": "/to/folder"
     *     }
     *   }
     * }
     */

    public function __construct($config)
    {
        if( ! isset($config->storage) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider config missing 'storage'.");

        $this->_storage = $config->storage;

        if( ! isset($config->base_url) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider config missing 'base_url'.");

        $this->_base_url = $config->base_url;

        if( substr($this->_storage,-1) == "/" )
            $this->_storage = substr($this->_storage,0,-1);

        if( ! is_dir($this->_storage) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider storage is not a directory.");

        if( ! is_writable($this->_storage) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryException("Provider storage is not writable.");

        try
        {
	        if( ! file_exists($this->_storage.'/'.$this->_data_file) )
	        {
	            $this->_data = (object)array(
                    'index' => array(),
	            	'containers' => (object)array(),
	            );

	            file_put_contents($this->_storage.'/'.$this->_data_file, json_encode($this->_data));
	        }
	        else
	        {
	        	$this->_data = json_decode(file_get_contents($this->_storage.'/'.$this->_data_file));
	        }
	    }
	    catch( \Exception $e )
	    {
	    	throw new \FunnyLookinHat\Purgatory\PurgatoryException("Could not load or initialize data storage.  Are write permissions setup correctly?");
	    }
    }

    public function getContainers()
    {
        $containers = array();

        foreach( $this->_data->index as $containerName )
            $containers[] = new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalContainer($this->_data->containers->{$containerName});

        return $containers;
    }

    public function listContainers()
    {
        return $this->_data->index;
    }

    public function getContainer($name)
    {
        if( ! isset($this->_data->containers->{$name}) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerDNEException("Could not fetch container: does not exist.");

        return new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalContainer($this->_data->containers->{$name});
    }

    public function createContainer($name)
    {
        if( isset($this->_data->containers->{$name}) )
            throw new \FunnyLookinHat\Purgatory\PurgatoryContainerExistsException("Could not create container: already exists.");

        mkdir($this->_storage.'/'.$name);

        $container = (object)array(
            'name' => $name,
            'path' => $this->_storage.'/'.$name,
            'url' => $this->_base_url.'/'.$name,
        );

        $this->_data->containers->{$name} = $container;
        $this->_data->index[] = $name;

        file_put_contents($this->_storage.'/'.$this->_data_file, json_encode($this->_data));

        return new \FunnyLookinHat\Purgatory\Purgatory\Provider\LocalProvider\LocalContainer($this->_data->containers->{$name});
    }
}
