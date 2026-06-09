.. index::
   single: Error handling

##############
Error handling
##############

The bundle draws a sharp line between failures a retry can fix and failures it
never will. That line is what makes an unattended, large-scale import safe to
run: transient problems heal themselves, and genuine problems surface instead
of silently looping.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
Two classes of failure
***************

Recoverable
===========

A recoverable failure is anything that might succeed if tried again later:

* ESPN returns a 5xx server error.
* A request times out or the network blips.
* ESPN momentarily returns an empty or malformed body for a resource that does
  exist.

These surface as ordinary exceptions from the importer (often originating in
the client). The handler lets them propagate, Messenger catches them, and your
``retry_strategy`` retries the message with backoff.

Unrecoverable
=============

An unrecoverable failure is one where retrying the identical message can never
help:

* The reference URL cannot be resolved to the ids the importer needs (a
  malformed or unexpected ``$ref``).
* A required **parent entity** is not present in the database (e.g. importing
  an athlete whose season was never imported).

These are thrown as ``UnrecoverableImportException`` inside the importer. The
handler catches it, logs it at ``critical``, and rethrows it as Symfony
Messenger's ``UnrecoverableMessageHandlingException`` so the message bypasses
the retry strategy and goes straight to the failure transport.

***************
How handlers implement the split
***************

Every handler follows the same two-arm catch structure:

.. code-block:: php

    try {
        $entity = $this->importer->buildEntityFromReference($message->reference);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // ... dispatch child messages ...
    } catch (UnrecoverableImportException $e) {
        // permanent: log and do not retry
        $this->importLogger->critical('… error', [
            'message'   => $e->getMessage(),
            'reference' => $message->reference,
            'previous'  => $e->getPrevious()?->getMessage(),
        ]);
        throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
    } catch (\Throwable $e) {
        // transient: log and let Messenger retry
        $this->importLogger->warning('… error', [
            'message'   => $e->getMessage(),
            'reference' => $message->reference,
            'previous'  => $e->getPrevious()?->getMessage(),
        ]);
        throw $e;
    }

The ordering matters: the specific ``UnrecoverableImportException`` arm comes
first, the catch-all ``\Throwable`` arm second.

***************
Where each exception is thrown
***************

``UnrecoverableImportException`` (in
``HansPeterOrding\EspnApiSymfonyBundle\Exception``) is thrown by importers for:

* URL-pattern resolution failures.
* Parent-entity-not-found-in-database (season, team, competition, athlete,
  competitor, season type, …).

A plain ``ImportException`` (or any other ``\Throwable``) is thrown for:

* The primary resource not being returned by the ESPN API (which may be a
  transient ESPN hiccup and is therefore worth retrying).

***************
Logging
***************

The bundle logs through an injected ``importLogger`` (a PSR-3 logger). By
convention:

* Unrecoverable failures are logged at **critical** — they need a human to
  look.
* Transient failures are logged at **warning** — they are expected noise that
  usually resolves on retry.

Wire ``importLogger`` to a dedicated Monolog channel if you want to isolate
import logs from the rest of your application.

***************
Inspecting failures
***************

Permanently-failed messages land on the failure transport, where you can
inspect the exception, the offending reference, and replay them once the root
cause is fixed:

.. code-block:: terminal

    $ php bin/console messenger:failed:show
    $ php bin/console messenger:failed:show <id> -vv
    $ php bin/console messenger:failed:retry <id>

A common cause of an unrecoverable failure is **import order**: dispatching a
child before its parent exists. If you see "… not found" criticals, check that
the parent tree was imported first, or dispatch the parent message and let the
cascade reach the child naturally.

***************
Idempotency and replays
***************

Because every importer uses a ``findByDtoOrCreateEntity`` style look-up,
replaying a message is safe: it updates the existing row rather than creating a
duplicate. This means you can retry failed messages liberally once you have
addressed the underlying cause, without worrying about polluting your data.

**********
Read next
**********

* :doc:`extending` — adding your own resources to the pipeline
