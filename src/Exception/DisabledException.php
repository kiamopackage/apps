<?php

namespace KiamoPackage\AppsBundle\Exception;


// Exception utilisée lorsque Kiamo Apps est désactivé
class DisabledException extends KiamoAppsException
{
    public function __construct() {
        parent::__construct("Kiamo Apps are disabled");
    }
}
