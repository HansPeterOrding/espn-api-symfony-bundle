.. index::
   single: Contribute

####################################
Contributing to EspnApiSymfonyBundle
####################################

First off, thanks for taking the time to contribute!

The following is a set of guidelines for contributing to
``EspnApiSymfonyBundle``. These are mostly guidelines, not rules. Use your best
judgment, and feel free to propose changes to this document in a pull request.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
Code of Conduct
***************

This project and everyone participating in it is governed by the
`Symfony Code of Conduct`_. By participating, you are expected to uphold this
code. Please report unacceptable behavior to bjoern.may@gmail.com.

***************************************************************
I don't want to read this whole thing I just have a question!!!
***************************************************************

.. note::

    Please don't file an issue to ask a question. You'll get faster results by
    using the resources below.

* For questions about the **raw ESPN data**, consult the unofficial community
  documentation of the ESPN API — ESPN publishes no official docs for these
  endpoints.
* For questions about **fetching** data, see the `espn-api-client`_
  documentation; this bundle only builds on top of it.
* For questions about **this bundle** (entities, importers, the message
  chain), prefer a discussion thread over an issue.

*********************
How Can I Contribute?
*********************

Reporting Bugs
==============

This section guides you through submitting a bug report for
``EspnApiSymfonyBundle``. Following these guidelines helps maintainers and the
community understand your report, reproduce the behavior, and find related
reports.

When you are creating a bug report, please
:ref:`include as many details as possible <bundle-how-do-i-submit-a-bug-report>`.

.. note::

    If you find a **Closed** issue that seems like the same thing you're
    experiencing, open a new issue and include a link to the original issue in
    the body of your new one.

.. _bundle-how-do-i-submit-a-bug-report:

How Do I Submit A (Good) Bug Report?
------------------------------------

Bugs are tracked as `GitHub issues`_. Create an issue on
`the repository <https://github.com/HansPeterOrding/espn-api-symfony-bundle>`_
and provide the following information.

Explain the problem and include additional details to help maintainers
reproduce the problem:

* **Use a clear and descriptive title** for the issue to identify the problem.
* **Describe the exact steps which reproduce the problem** in as many details
  as possible. Which message did you dispatch, with which reference and
  import-control array? When listing steps, **don't just say what you did, but
  explain how you did it**.
* **Provide specific examples to demonstrate the steps**. Include the
  dispatched message, the reference URL, and (a trimmed copy of) the ESPN JSON
  if relevant, in `Markdown code blocks`_.
* **Describe the behavior you observed** and point out what exactly is the
  problem with that behavior. Include the relevant log lines — the bundle logs
  import failures with the offending reference.
* **Explain which behavior you expected to see instead and why.**

Provide more context by answering these questions:

* **Did the problem start happening recently** (e.g. after updating the bundle)
  or was this always a problem?
* If the problem started happening recently, **can you reproduce the problem in
  an older version?** What's the most recent version in which it doesn't
  happen?
* **Can you reliably reproduce the issue?** If not, provide details about how
  often it happens and under which conditions. Bear in mind the ESPN API is
  itself occasionally inconsistent, and that import order matters (a child
  dispatched before its parent will fail).

Include details about your configuration and environment:

* **Which version of EspnApiSymfonyBundle and espn-api-client are you using?**
* **Which Symfony and PHP versions are you using?**
* **Which database platform and version?** (Some entities use JSON columns,
  decimals, and join tables.)
* **How is Messenger configured** — which transports, routing, and retry
  strategy?

Suggesting Enhancements
=======================

This section guides you through submitting an enhancement suggestion for
``EspnApiSymfonyBundle``, including completely new features and minor
improvements to existing functionality.

When you are creating an enhancement suggestion, please
:ref:`include as many details as possible <bundle-how-do-i-submit-an-enhancement>`.

.. _bundle-how-do-i-submit-an-enhancement:

How Do I Submit A (Good) Enhancement Suggestion?
------------------------------------------------

Enhancement suggestions are tracked as `GitHub issues`_. Create an issue on the
repository and provide the following information:

* **Use a clear and descriptive title** for the issue to identify the
  suggestion.
* **Provide a step-by-step description of the suggested enhancement** in as
  many details as possible.
* **Provide specific examples to demonstrate the steps**, in
  `Markdown code blocks`_.
* **Describe the current behavior** and **explain which behavior you expected
  to see instead** and why.
* **Explain why this enhancement would be useful** to most users and isn't
  something better implemented in a consuming application.
* **Specify which version of the bundle you're using.**

Pull Requests
=============

The process described here has several goals:

* Maintain ``EspnApiSymfonyBundle``'s quality
* Fix problems that are important to users
* Engage the community in working toward the best possible bundle
* Enable a sustainable system for maintainers to review contributions

Please follow these steps to have your contribution considered by the
maintainers:

#. Formulate what your pull request is intended to do.
#. Follow the :ref:`styleguides <bundle-styleguides>`.
#. When adding a resource, include **all** of its layers in the same pull
   request — entity, repository, converter, importer, message, and handler —
   plus the service registrations and the messenger routing. See
   :doc:`extending`.
#. Respect the layering: converters set only scalars and references; importers
   own all entity-to-entity connections.
#. Cover new behavior with tests where practical.

While the prerequisites above must be satisfied prior to having your pull
request reviewed, the reviewer(s) may ask you to complete additional tests or
other changes before your pull request can be ultimately accepted.

.. _bundle-styleguides:

***********
Styleguides
***********

Git Commit Messages
===================

* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to…" not "Moves cursor to…")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally after the first line

PHP Styleguide
==============

Stick to:

* `PSR-1`_
* `PSR-12`_
* `PSR Naming Conventions`_
* `PSR-4 Autoloading Standard`_

In addition, follow the bundle's established conventions:

* Entities use ``espnId``, bigint identity keys, nullable columns, the
  ``SyncTimestampsTrait``, and enums for fixed vocabularies.
* Free-form text columns are ``TEXT``, not ``VARCHAR(255)``.
* Converters set scalars and reference strings only; importers perform all
  entity connections, from the owning side of an association.
* Handlers persist exactly the entity their importer returns, then dispatch
  children gated by ``shouldImport()``.
* Use ``UnrecoverableImportException`` for permanent failures and a plain
  ``ImportException`` (or other ``\Throwable``) for retryable ones.
* Columns that collide with SQL reserved words are mapped to a safe column
  name explicitly.

Documentation Styleguide
========================

* Use `reStructuredText`_ and remain compatible with `Read the Docs`_.
* Every deep-dive topic lives in its own file and is linked from
  ``index.rst`` through the ``toctree``.

.. _Symfony Code of Conduct: https://symfony.com/doc/current/contributing/code_of_conduct/code_of_conduct.html
.. _espn-api-client: https://github.com/HansPeterOrding/espn-api-client
.. _GitHub issues: https://guides.github.com/features/issues/
.. _Markdown code blocks: https://help.github.com/articles/markdown-basics/#multiple-lines
.. _PSR-1: https://www.php-fig.org/psr/psr-1/
.. _PSR-12: https://www.php-fig.org/psr/psr-12/
.. _PSR Naming Conventions: https://www.php-fig.org/bylaws/psr-naming-conventions/
.. _PSR-4 Autoloading Standard: https://www.php-fig.org/psr/psr-4/
.. _reStructuredText: https://www.sphinx-doc.org/
.. _Read the Docs: https://readthedocs.org/
