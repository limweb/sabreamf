# Distinction (case sensitive terms) #

SabreAMF - is the PHP5 AMF framework, but **sabreamf** - is the Drupal module

# What is it? #

Drupal 6 http://www.drupal.org is a well known open-source CMS(content management system). This module allows a way to communication between Drupal and Flash/Flex clients through AMF protocol.

# How **sabreamf** Drupal module does all this #

**sabreamf** module was greatly inspired by an existing Drupal (AMFPHP at http://drupal.org/project/amfphp) module that offered the same kind of functionality but that was ignored for a long period of time, although it seems life is once again breath upon it(thank you Scott).

**sabreamf** module needs Drupal Services http://drupal.org/project/Services module to be installed and enabled.

# How to check if the gateway is working #

If you installed Drupal 6 on your localhost (wamp, lampp server), then you can login as administrator user and point your browser to gateway http://localhost/drupal/services/sabreamf url.
If all goes well, you will see a black screen with a nice SabreAMF+Drupal logo in the middle(another idea inspired by RubyAMF's http://code.google.com/p/rubyamf screen non amf clients attempting connection)

Example:

![http://sabreamf.googlecode.com/svn/trunk/integrations/drupal/module/sabreamf/media/images/gateway.png](http://sabreamf.googlecode.com/svn/trunk/integrations/drupal/module/sabreamf/media/images/gateway.png)

It is recommended that you read all the txt files coming with this module, as you would find useful information

# LICENCE #

Drupal comes with a GPL license, where SabreAMF has its own license, try to contact the people behind SabreAMF framework for their support if your commercial projects requires draconian license measures.
The Drupal 6 **sabreamf** module comes with a non restrictive license, the will is to have 0(zero) restrictions on this code or derivations (check COPYING.txt), other files included with this package can have their own applicable license if specified (or if technology itself requires it without mentioning)