## What is SabreAMF ##

SabreAMF allows you to create flash remoting (a.k.a. AMF-) webservices using PHP. These services can be used by flash or flex as a replacement for for example XML documents or 'sendAndLoad'. The main advantages are that it is native to flash and has a relatively easy API (you don't have to parse xml documents), and that its often faster to load.

Because of the binary format the HTTP packages are also often smaller, especially for bigger chunks of data.

## How does SabreAMF compare to WebOrb for PHP or AMFPHP ##

If you are a beginner or you are looking for a very complete out of the box AMF solution, i would definitely advice to use [AMFPHP](http://www.amfphp.org/) or [WebOrb](http://www.themidnightcoders.com/weborb/php/). Its pretty much install-and-go with those products.

If you are looking for a more low-level library to parse AMF data and requests, or you want to integrate flash remoting into your existing (webservices-)framework SabreAMF might be a better choice. It is written to not pollute the global namespace, apply 'proper' OOP and run in PHP 5.2 strict mode.

Additionally, the package also contains an AMF client; which can be useful to test other services or poke through the alleged security of binary formats and crossdomain.xml.

## What doesn't it do ##

  * It doesn't load services and execute methods for you
  * It doesn't map VO's automatically
  * It doesn't come with a service browser
  * Probably more..

## Can I use SabreAMF with PHP 5.1 ##

Yes, the only reason PHP5.2 is a requirement, is for the new DateTime object. If you create a class like this:

```
<?php
class DateTime {
 
   private $time;

   function __construct($datestring) {

      $this->time = strtotime($datestring);

   }

   function format($dateformat) {

      return date($dateformat,$this->time);

   }
} 
?> 
```

It will work just fine in PHP 5.1. PHP 5.0 is not supported and its use is discouraged in general because its kinda slow and buggy.

## Where do I start? ##

If you want to create an amf gateway, check out the [CallbackServer](CallbackServer.md) documentation.

The tgz package contains an examples folder, which should help you get started writing a server or client.

Also, some more advanced examples can be found on the [semi-offical blog](http://www.rooftopsolutions.nl/code/?a=a&p=SabreAMF)

## How can I help out? ##

  * submit bug reports
  * fix bugs
  * write documentation
  * suggest features