<?php

namespace KiamoPackage\AppsBundle\Exception;

// Exception utilisée lorsque l'API retourne un code HTTP différent de 200 (400, 500...)
class ApiErrorException extends KiamoAppsException
{
    /** @var int */
    private $httpCode;

    public function __construct(int $httpCode) {
        parent::__construct("Kiamo Apps API return error HTTP code ".$httpCode);

        $this->httpCode = $httpCode;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int {
        return $this->httpCode;
    }
}
