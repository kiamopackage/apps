<?php

namespace KiamoPackage\AppsBundle\Exception;

// Exception utilisée lorsqu'aucun token valide n'est présent en base
// pour pouvoir faire un appel à l'API Kiamo Apps
class NoTokenException extends TokenException
{
    public function __construct() {
        parent::__construct("No token available");
    }
}
