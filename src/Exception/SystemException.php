<?php

namespace KiamoPackage\AppsBundle\Exception;

use Throwable;

// Exception utilisée lorsque l'une des fonctions utilisée en locale a généré une exception
// (cURL, iconv...)
class SystemException extends KiamoAppsException
{
    public function __construct(string $message, Throwable $previous = null) {
        parent::__construct("The following error occurred: ".$message, $previous);
    }
}
