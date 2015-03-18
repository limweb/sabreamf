# What is it? #

Flash remoting has a standard way to deal with authentication. AMF0 and AMF3 deal with this differently, but this is abstracted in SabreAMF.

With the flash remoting components, you would use setCredentials(username,password) on the service object, in Flex you need to call setRemoteCredentials(username,password) on the RemoteObject to pass credentials.

# How to capture it? #

The servicebrowser has an onAuthenticate event you can use to capture the authentication.

We'll use the example from the CallbackServer page.

```
<?php

  // First we'll create the server object
  require_once 'SabreAMF/CallbackServer.php';
  $server = new SabreAMF_CallbackServer();

  // setup the callback
  $server->onInvokeService = 'myCallback';

  // parse the request and spit out the response
  $server->exec();

  function myCallback($serviceName, $methodName, $arguments) {

    // You can use the servicename to lookup and instantiate a service class
    $serviceObject = new $serviceName;

    // check the php manual if you don't know what this function does
    return call_user_func_array(array($serviceObject,$methodName),$arguments);

  }


?>
```

To add the authenticate event, we need to add the following line before `$server->exec()`.

```
<?php
  
   $server->onAuthenticate = 'myAuthFunction';

?>
```

The `myAuthFunction` should look like this:

```
<?

  function myAuthFunction($username, $password) {

     if ( /* authenticate the user */ ) {

        return true;

     } else {

        throw new Exception('User could not be logged in');

     }
         
  }

?>
```

# Use in practice #

Overall authentication in AMF is badly designed, it's completely different in AMF0 and AMF3 and each method has nasty side effects.

  * AMF0: The username and password are sent over the wire for every single request as a header. It is however not easily possible to send back a 'login failed' message back, this would only really possible through the result of the actual method being called.
  * AMF3: The username and password are sent only once, and you can return a proper message if the user couldn't log in for some reason. However, there is no such thing as a session state, rendering authentication pretty much useless. It is possible to use cookies to maintain session information, but this does not work with Internet Explorer (tested on 6).

## Solutions ##

I'd strongly recommend building a custom login/session system. The easiest way to do this is by having a simple login service/method and returning a session id (or throw an exception when credentials are incorrect).

After this always send along the session id with every AMF request. One common solution for this is to change the gateway url to include the session id. You can re-attach the request to the session by using php's [session\_id function](http://www.php.net/session_id)

# Session support #

Although AMF requests ignore HTTP cookies, it is possible to automatically attach a session id through AMF0.

The server can set a header in the AMF response, telling the client to modify the gateway url. This is only supported in AMF0, so if you want to do this using AMF3, you'll need to roll your own system for this.

If you have an instance of SabreAMF\_Server or SabreAMF\_CallbackServer, you call the addHeader method, for example:

```

// assuming $server is an instance of SabreAMF_CallbackServer
$server->addHeader('AppendToGatewayUrl',false,'?session=' . session_id());

```

We'll also need to recognize the session id, so here's a bit more complete example:

```

if (isset($_GET['session'])) {

  session_id($_GET['session']);
  session_start();

} else {

  session_start();
  $server->addHeader('AppendToGatewayUrl',false,'?session=' . session_id());

}

```

## Security note ##

You are strongly recommended to verify if a session exists if it is supplied in the url. If you simply start a session with whatever id is supplied in the url, you could potentially make stealing cookies a lot easier.

Also, it is recommended to change the session id whenever a user logs in, or logs out..

## Alternative header ##

Instead of 'AppendToGatewayUrl' it is also possible to make use of the 'ReplaceGatewayUrl' header. As the name indicates, it replaces the gateway url altogether and this can be useful if you need to replace an existing session id. This header expects an absolute url.