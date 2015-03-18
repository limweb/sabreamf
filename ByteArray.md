# What is it? #

The flash/flex bytearray is an object that allows you to deal with binary data. [documentation](http://livedocs.adobe.com/flex/2/langref/flash/utils/ByteArray.html)

# How SabreAMF handles it #

SabreAMF has an `SabreAMF_ByteArray` class. It is actually extremely simple, because strings in PHP5 are treated as binary data.. All it really does is wrap a string in an object.

# Example, sending back a jpeg #

This example assumes that mypicture.jpg exists in the current folder. This method belongs in most cases in a service object.

```
<?php

  function getPicture() {

     $data = file_get_contents('mypicture.jpg');
     $byteArray = new SabreAMF_ByteArray($data);

     return $byteArray;

  }

?>
```