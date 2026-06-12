ESPN API Symfony bundle
========================

<!-- badges: start -->
<!-- ![GitHub release (latest by date)](https://img.shields.io/github/v/release/HansPeterOrding/espn-api-symfony-bundle?label=development%20version) -->
<!-- badges: end -->

Symfony bundle that imports ESPN NFL data into Doctrine entities via an asynchronous, Messenger-driven import pipeline, built on top of [`espn-api-client`](https://github.com/HansPeterOrding/espn-api-client).

Package contains:
* Doctrine entities for the ESPN NFL data model
* Converters and importers for every entity
* Symfony Messenger messages and handlers forming the import chain
* Configurable import-control flags

Documentation
-------------

Read the tutorial here:

Documentation for EspnApiSymfonyBundle can be found at [`Read the docs`](https://espn-api-symfony-bundle.readthedocs.io/en/latest)

License
-------

This bundle is released under the MIT license. See the included [LICENSE](LICENSE) file for more information.
