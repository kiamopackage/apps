<?php

namespace KiamoPackage\AppsBundle\Exception;

// Exception utilisée lorsqu'on tente d'utiliser un token invalide. Peut survenir:
//      - avant une appel API si on détecte que le token à une date d'expiration dépassée en base
//      - en réponse à un appel API (réponse du serveur Kiamo Apps)
class InvalidTokenException extends TokenException
{
    public function __construct() {
        parent::__construct("Invalid API token");
    }
}
