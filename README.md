Loewenstark_Cache
=====================

Facts
-----
- [extension on GitHub](https://github.com/mklooss/Loewenstark_Cache)

Description
-----------
Cache Fallback, if a cache backend is not available, then use a fallback
Default fallback is "Cm_Cache_Backend_File"

```xml
<?xml version="1.0"?>
<config>
    <global>
        <cache>
            <backend>Cm_Cache_Backend_Mongo</backend>
            <fallback>cache_redis</fallback>
        </cache>
        <cache_redis>
            <backend>Cm_Cache_Backend_Redis</backend>
        </cache_redis>
    </global>
</config>
```

Requirements
------------
- PHP >= 5.2.13
- Magento

Compatibility
-------------
- Magento >= 1.7

Installation Instructions
-------------------------

Uninstallation
--------------
Remove all extension files from your Magento installation

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/mklooss/Loewenstark_Cache/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Mathis Klooß
[http://www.mage-profis.de/](http://www.mage-profis.de/)
[@gunah_eu](https://twitter.com/gunah_eu)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2013 Mathis Klooss
