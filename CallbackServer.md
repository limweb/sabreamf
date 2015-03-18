# What is it? #

The callback server is a class that helps you go through the process parsing the flash remoting request and sending back a response. It's the easiest way to get a flash remoting gateway up and running.

# Setup #

```
<?php

// First we'll create the server object
require_once 'SabreAMF/CallbackServer.php';
$server = new SabreAMF_CallbackServer();

// setup the callback
$server->onInvokeService = 'myCallback';

// parse the request and spit out the response
$server->exec();

?>
```

The previous example assumes that the SabreAMF directory is in your current working directory or include\_path. If this is not the case, you can add it to the include\_path using the following example:

```
<?php

set_include_path( get_include_path() . PATH_SEPARATOR . '/path/to/sabreamf/directory');

?>
```

# Callback function #

For every methodcall made from flash, a 'callback' function is called. In the previous example the callback function was called `myCallback`. You have to create this function yourself. The function should have 3 arguments. For example:

```
<?php

  function myCallback($serviceName, $methodName, $arguments) {

    // You can use the servicename to lookup and instantiate a service class
    $serviceObject = new $serviceName;

    // check the php manual if you don't know what this function does
    return call_user_func_array(array($serviceObject,$methodName),$arguments);

  }

?>
```

# Next steps #

The last example is actually a working example and is a basic amf gateway. It expects the class of the service objects to be the exact same as the servicepath, used from flash.

I'd not recommend this, because it allows anyone to instantiate objects of any class and call any method; which could result in security holes.