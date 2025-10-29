# nflfastr-symfony-bundle

<!-- badges: start -->

[![Travis build
status](https://travis-ci.com/HansPeterOrding/espn-api-symfony-bundle.svg?branch=main)](https://travis-ci.com/github/HansPeterOrding/sleeper-api-symfony-bundle)
[![Twitter
Follow](https://img.shields.io/twitter/follow/bjoernmay78.svg?style=social)](https://twitter.com/bjoernmay78)
<!-- ![GitHub release (latest by date)](https://img.shields.io/github/v/release/HansPeterOrding/espn-api-symfony-bundle?label=development%20version) -->
<!-- badges: end -->

Symfony bundle to import data from ESPN API to symfony.

Bundle contains:
* Entities
* Migrations
* Repositories
* Import commands

Documentation
-------------

Documentation for EspnApiSymfonyBundle is in [`doc/index.md`](doc/index.md)

Installation
------------

Installation instructions can be found in the [documentation](doc/setup.md)

Versions & Dependencies
-----------------------

Version 0.1 of the EspnApiSymfonyBundle is compatible with ESPN API as of 2025-10-23. It requires Symfony 5.0 or greater. When using
Symfony Flex there is also a [recipe to ease the setup](https://github.com/symfony/recipes-contrib/tree/master/hans-peter-ording/espn-api-symfony-bundle/0.1).
Earlier versions of the EspnApiSymfonyBundle are not maintained anymore and only work with older versions of the dependencies.
The following table shows the compatibilities of different versions of the bundle.

| EspnApiSymfonyBundle | EspnAPI | Symfony    | PHP   |
|----------------------|---------| ---------- |-------|
| [0.1] (master)       | ^1.0    | ^5.0       | >=8.1 |

License
-------

This bundle is released under the MIT license. See the included [LICENSE](LICENSE) file for more information.




___

@todo: move this to docs folder
@todo: hooks and events?


## Entities

Why entities
Structure in general (with storing complete data)
Details on fieldmappings

## Import strategies

Console or direct
Pros and cons
For console:
multiple workers in parallel
timings
Memory restrictions

## Extending this bundle

## Contribute / Community

## Outlook

Optimized indexes, hooks, events, ...
