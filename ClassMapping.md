# Introduction #

SabreAMF allows you to automatically map PHP classes to Flash classes. This is often used for the so called [Data Transfer Objects](http://en.wikipedia.org/wiki/Data_Transfer_Object) or the more popular term Value Object or VO.

There's a bunch of ways this can be implemented with SabreAMF.

# TypedObjects #

If you come from an AMFPHP background, you can normally specify a classname for every array with data using the _explicitType key._

AMFPHP example:

```
<?php

  $data = array(
    'property1' => 'value1',
    'property2' => 'value2',
  );

  $data['_explicitType'] = 'mypackage.MyFlashClass';

?>
```

SabreAMF has a more OOP-way to handle this, by wrapping it in an object..

```
<?php

  $data = array(
    'property1' => 'value1',
    'property2' => 'value2',
  );

  $object = new SabreAMF_TypedObject('mypackage.MyFlashClass',$data);

?>
```

Typed Objects, or Custom Classes are also returned as SabreAMF\_TypedObject when they originate from a flash methodcall. Check out the source of the class to find out more details.

# Using ITypedObject #

SabreAMF also has an `SabreAMF_ITypedObject` interface. If you implement this interface you can create custom method to return serialized data or the name of the class.

The interface forces you to implement the getAMFClassName and getAMFData methods. Check out the source of the interface for more details. This is especially useful if you need to integrate custom serialization in existing components of your framework or application.

# The ClassMapper #

The TypedObjects only really allow you to convert classes 1-way, from PHP data to a Flash Class. The ClassMapper can also map Flash classes to PHP classes, specifically for use with VO's.

The `SabreAMF_ClassMapper` contains these mappings. Using it is easy:

```
<?php

  SabreAMF_ClassMapper::registerClass('yourpackage.YourFlashClass','YourPHPClass');

?>
```


## Custom classmapping ##

In some cases you might want to be able to automatically map all classes from a specific folder. In this case you'd need the custom classmapping. It works similar to the CallbackServer, with event handlers.

There are two events, onGetRemoteClass and onGetLocalClass. The first to map a PHP class to a Flash class, and the second to do the reverse. Examples:

```
<?php

  SabreAMF_ClassMapper::$onGetLocalClass = 'flashToPHPClass';
  SabreAMF_ClassMapper::$onGetRemoteClass = 'PHPToFlashClass';

?>
```

These callback classes both receive a classname as a string, and should also return a string.