# What is it? #

The [Flex ArrayCollection](http://livedocs.adobe.com/flex/2/langref/mx/collections/ArrayCollection.html) is a wrapper around a normal flash/flex array. It adds more functionality, making normal arrays usable in some of the other components of the flex framework.

# How SabreAMF deals with it #

SabreAMF has an object thats similar to flex' ArrayCollection. All ArrayCollections are automatically mapped to SabreAMF\_ArrayCollection.

SabreAMF\_ArrayCollections uses [PHP's ArrayObject class](http://www.php.net/~helly/php/ext/spl/classArrayObject.html) to store its data.

# How to send back arrays as ArrayCollections back to flex #

To send an ArrayCollection back to flex, you can simply instantiate `SabreAMF_ArrayCollection` and return it from your serviceclass objects.

The ArrayCollection works pretty much just like a normal array; The biggest exception is that it doesn't work with some of the PHP array manipulation functions.

```
<?php

  $data = array();
  $data[] = array('property1'=>'yo','property2'=>'test2');
  $data[] = array('property1'=>'foo','property2'=>'bar');

  $arrayCollection = new SabreAMF_ArrayCollection($data);

  foreach($arrayCollection as $row) {
 
     // So yea, you can just loop through it like a normal array (done through IteratorAggregate)

  }

  // Or get values straight from a certain row (done through ArrayAccess)
  echo($arrayCollection[0]['property1']);

  // Or get the total number of rows (done through countable)

  echo(count($arrayCollection));

  // In the case you need a normal array, based on the ArrayAccess class
 
  $normalArray = iterator_to_array($arrayCollection);

?>
```