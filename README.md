purgatory
=========

Where things to go wait.

## Basic Usage

Everything begins with initializing a provider; this can include platform-specific options, but all API calls after this point are platform agnostic.

```
$purgatory = new Purgatory((object)array(
    'provider' => 'rackspace',
    'region' => 'ORD',
    'username' => 'myUsername',
    'apiKey' => 'myApiKey',
));
```

All functions that begin with get and create will return an object, whereas functions that begin with list return strings ( of names ).

Containers are the entry point to manipulate objects.

```
$container = $purgatory->getContainer('someContainer');
$containers = $purgatory->getContainers();
$containerNames = $purgatory->listContainers();
$container = $purgatory->createContainer('myContainer');
```

Once you have a container, you can manipulate objects.

```
$name = $container->getName();
$object = $container->getObject('myObject');
$object = $container->createObject('someObject', 'path/to/someObject.file');
$objects = $container->getAllObjects();
$objectNames = $container->listAllObjects();
$container->enableCdn();
$container->disableCdn();
$url = $container->getUrl();
$sslUrl = $container->getSslUrl();
```

Objects also have several actions.

```
$name = $object->getName();
$object->update($path);
$md5 = $object->getChecksum();
$url = $object->getUrl();
$sslUrl = $object->getSslUrl();
```
