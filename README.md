purgatory
=========

Where things to go wait.

##Ideal Use Case

```
<?php

use FunnyLookinHat\Purgatory;

$config = (object)array(
    'provider' => 'rackspace',
    'username' => 'myUsername',
    'apiKey' => 'mySuperSecretApiKey',
    'region' => 'ORD',
);

$purgatory = new Purgatory($config);

try {
    $container = $purgatory->getContainer('myContainer');
}
catch( PurgatoryContainerDNEException $e )
{
    // Container already exists - create one...
    try
    {
        $container = $purgatory->createContainer('myContainer');
    }
    catch( PurgatoryContainerException $e )
    {
        // An error occurred when creating the container.
    }
}
catch( PurgatoryContainerException $e )
{
    // An error occurred trying to fetch the container.
    return;
}
catch( PurgatoryException $e )
{
    // Most likely a credential or provider error.
	// This can also be thrown if the particular abstract function is not
	// supported by the provider.
    return;
}

try
{
    $file = $container->getFile('myFile');
}
catch( PurgatoryFileDNEException $e )
{
	// File doesn't exist.
	return;
}
catch( PurgatoryFileException $e )
{
	// An error occurred fetching the file.
	return;
}
catch( PurgatoryException $e )
{
	// Most likely a credential or provider error.
	// This can also be thrown if the particular abstract function is not
	// supported by the provider.
	return;
}
```