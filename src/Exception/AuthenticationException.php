<?php

namespace KiamoPackage\AppsBundle\Exception;

// Exception utilisée lorsque le serveur Kiamo Apps retourne une erreur d'authentification
// (clientID/clientSecret invalide...)
class AuthenticationException extends KiamoAppsException
{
    public function __construct() {
        parent::__construct("Invalid authentication information");
    }
}
