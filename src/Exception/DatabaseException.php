<?php

namespace KiamoPackage\AppsBundle\Exception;

use Throwable;

// Exception utilisée lorsqu'un à une des fonctions de Doctrine a généré une exception
// (find, flush...)
class DatabaseException extends KiamoAppsException
{
    public function __construct(Throwable $previous = null) {
        parent::__construct("The database call has returned an exception: ".$previous->getMessage(), $previous);
    }
}
