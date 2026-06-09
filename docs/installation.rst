.. index::
   single: Installation

############
Installation
############

.. contents:: Table of contents
   :depth: 2
   :local:

*************
Requirements
*************

* PHP 8.3 or higher
* Symfony 6.4 or 7.x (``symfony/messenger``, ``symfony/serializer``)
* Doctrine ORM (``doctrine/orm``, ``doctrine/doctrine-bundle``)
* The `espn-api-client`_ package and a PSR-18 HTTP client (see the client's
  own installation guide)

************
Via Composer
************

.. code-block:: terminal

    $ composer require hanspeterording/espn-api-symfony-bundle

This pulls in ``espn-api-client`` as a dependency. You still need a PSR-18
HTTP client and PSR-17 factories available in your application — the client
documentation explains how to provide them (auto-discovery or explicit
wiring).

*********************
Registering the bundle
*********************

The bundle ships no Symfony Flex recipe, so register it manually in
``config/bundles.php``:

.. code-block:: php

    // config/bundles.php
    return [
        // ...
        HansPeterOrding\EspnApiSymfonyBundle\EspnApiSymfonyBundle::class => ['all' => true],
    ];

The bundle's ``DependencyInjection`` extension loads its service definitions
automatically as soon as the bundle is registered, so there is nothing further
to import — the repositories, converters, importers, and message handlers are
wired for you.

.. note::

    The bundle is configured entirely through the service definitions it
    ships (see :doc:`architecture`). There is no user-facing configuration tree
    to fill in: the extension exists only to load the bundle's own services. If
    configurable options are introduced in a future version, a Flex recipe will
    be added at that point.

*********************
Registering the entity mappings
*********************

The bundle's entities live in its own ``src/Entity`` directory, so Doctrine
needs a mapping entry pointing at them. Add the following to your
``config/packages/doctrine.yaml`` under ``doctrine.orm.mappings``:

.. code-block:: yaml

    # config/packages/doctrine.yaml
    doctrine:
        orm:
            mappings:
                HpoEspnApiSymfonyBundle:
                    type: attribute
                    dir: '%kernel.project_dir%/vendor/hans-peter-ording/espn-api-symfony-bundle/src/Entity'
                    prefix: 'HansPeterOrding\EspnApiSymfonyBundle\Entity'
                    alias: 'HpoEspnApiSymfony'

This makes the entities visible to the ORM, which is what allows
``make:migration`` to detect them and generate the schema (see below).

*********************
Configuring Messenger
*********************

The import pipeline is asynchronous and depends on Symfony Messenger. The
bundle ships two distributable configuration files you copy and adapt:

* ``messenger.yaml.dist`` — the production transport, routing, and retry setup
* ``messenger.dev.yaml.dist`` — a development-friendly variant

Copy the relevant one into your application and adjust transports, routing,
and the retry strategy to your needs:

.. code-block:: terminal

    $ cp vendor/hanspeterording/espn-api-symfony-bundle/messenger.yaml.dist \
         config/packages/messenger.yaml

See :doc:`messenger` for what the routing and retry settings mean and how to
tune them.

*********************
Creating the schema
*********************

The bundle does **not** ship Doctrine migrations, because the right migration
depends on your existing schema and database platform. Once the entity mapping
is registered (see above), generate a migration with MakerBundle — the bundle's
entities are detected through that mapping:

.. code-block:: terminal

    $ php bin/console make:migration

Review the generated migration, then apply it:

.. code-block:: terminal

    $ php bin/console doctrine:migrations:migrate

.. note::

    Re-run ``make:migration`` whenever you upgrade the bundle, since new ESPN
    resources or relationship changes may add tables or columns. Always review
    the diff before applying — the bundle's entities use a few platform
    features (JSON columns, decimal precision, join tables) that are worth a
    quick sanity check on your target database.

*********************
Verifying the install
*********************

Confirm the entity mappings are valid before your first import:

.. code-block:: terminal

    $ php bin/console doctrine:schema:validate

Then start a Messenger worker and dispatch a small import (a single team is a
good smoke test — see :doc:`import_control`) to confirm the pipeline runs end
to end.

**********
Read next
**********

* :doc:`architecture` — how the pieces fit together
* :doc:`import_control` — choosing what to import

.. _espn-api-client: https://github.com/HansPeterOrding/espn-api-client
