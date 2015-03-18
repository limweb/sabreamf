# What is it? #

Most of the objects/classes sent over the wire simply need all their properties encoded and decoded. In some cases it might make more sense to send over in a custom format. This allows you to control exactly what you want you want to send over, so you can hide irrelevant data or save bandwidth.

## ArrayCollection ##

One class that makes use of this is the [ArrayCollection](FlexArrayCollection.md). An implementation is included with SabreAMF, so that would also make a good example.

# How to go about it #

First, you need to make sure you class is part of the ClassMapper. The class needs to implement the SabreAMF\_Externalized interface. This forces you to implement 2 methods.

  1. writeExternal - This method should return exactly the data you want to serialize.
  1. readExternal - This method expects one parameter, which contains the serialized data. This method should deserialize the data.

Externalized objects need to be setup in both PHP and Flex. writeExternal and readExternal can receive/return data of any type (int, string, other objects..), but this would need to be manually decoded from within flex.

[Flex IExternalizeable documentation](http://livedocs.adobe.com/flex/201/langref/flash/utils/IExternalizable.html)