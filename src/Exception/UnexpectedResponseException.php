<?php

namespace KiamoPackage\AppsBundle\Exception;

use Throwable;

// Exception utilisée lorsque l'API retourne une réponse avec un code HTTP 200
// mais que la réponse n'est pas celle attendue (JSON invalide, entrées absentes...)
class UnexpectedResponseException extends KiamoAppsException
{
    /** @var string */
    private $response;

    /**
     * @param string $message
     * @param string $response
     * @param Throwable|null $previous
     */
    public function __construct(
        string $response,
        string $message = 'Kiamo Apps API return an unexpected response',
        Throwable $previous = null
    ) {
        parent::__construct($message, $previous);

        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponse(): string {
        return $this->response;
    }

    public static function createJsonInvalid(string $response, Throwable $previous = null) {
        return new self($response, 'Kiamo Apps API return an invalid JSON response', $previous);
    }

    public static function createInvalidJsonContent(string $response, Throwable $previous = null) {
        return new self($response, 'Kiamo Apps API return a JSON response with bad content', $previous);
    }
}
