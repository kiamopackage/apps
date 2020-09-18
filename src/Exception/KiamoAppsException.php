<?php

namespace KiamoPackage\AppsBundle\Exception;

use Exception;
use Throwable;

abstract class KiamoAppsException extends Exception
{
    public function __construct(string $message, Throwable $previous = null) {
        parent::__construct($message, 0, $previous);
    }
}
