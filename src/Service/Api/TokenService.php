<?php

namespace KiamoPackage\AppsBundle\Service\Api;

use DateInterval;
use DateTime;
use KiamoPackage\AppsBundle\Entity\Config;
use KiamoPackage\AppsBundle\Exception\ApiErrorException;
use KiamoPackage\AppsBundle\Exception\AuthenticationException;
use KiamoPackage\AppsBundle\Exception\SystemException;
use KiamoPackage\AppsBundle\Exception\UnexpectedResponseException;
use KiamoPackage\AppsBundle\Service\HttpRequest\UnauthenticateApiRequestService;
use KiamoPackage\AppsBundle\Service\LoggerService;
use Throwable;

class TokenService
{
    /** @var UnauthenticateApiRequestService */
    private $apiRequestService;

    /** @var LoggerService */
    private $logger;

    /**
     * @param UnauthenticateApiRequestService $apiRequestService
     * @param LoggerService $logger
     */
    public function __construct(UnauthenticateApiRequestService $apiRequestService, LoggerService $logger) {
        $this->apiRequestService = $apiRequestService;
        $this->logger = $logger;
    }

    /**
     * Fonction qui récupère un token auprès le l'API token de Kiamo Apps
     * et met à jour l'objet Config passé en paramètre avec les informations de token récupérées
     * @param Config $config
     * @throws ApiErrorException HTTP code 500 - Problème inconnu sur le serveur
     * @throws AuthenticationException HTTP code 400 - identifiants invalide
     * @throws SystemException Problème divers lié aux connexion cURL ou MySQL
     * @throws UnexpectedResponseException HTTP code 200 - Avec un contenu différent de celui attendu
     */
    public function generate(Config $config): void {
        $response = $this->apiRequestService->post(
            'api/services/tokens',
            [
                'grant_type' => 'client_credentials',
                'client_id' => $config->getClientId(),
                'client_secret' => $config->getClientSecret(),
                'scope' => "server",
            ]
        );

        $code = $response->getCode();
        if ($code !== 200) {
            if ($code === 400) {
                $this->logger->error('API tokens return an authentication failed response');
                throw new AuthenticationException();
            } else {
                $this->logger->error('API tokens return HTTP code '.$code);
                throw new ApiErrorException($code);
            }
        }

        $body = $response->getBody();

        try {
            $jsonResponse = json_decode($body, true);
        } catch (Throwable $e) {
            $this->logger->error('API tokens return an invalid JSON');
            throw UnexpectedResponseException::createJsonInvalid($body, $e);
        }

        // On s'assure que le JSON reçu correspond bien à ce qui est attendu:
        //    - il doit s'agir d'un tableau
        //    - avec une entrée access_token qui doit contenir une chaîne de caractères
        //    - avec une entrée expires_in qui doit contenir un entier
        //      ou, a minima, une chaîne de caractères ne contenant que des chiffres
        if (
            is_array($jsonResponse) === false
            || array_key_exists('access_token', $jsonResponse) === false
            || is_string($jsonResponse['access_token']) === false
            || array_key_exists('expires_in', $jsonResponse) === false
            || (
                is_int($jsonResponse['expires_in']) === false
                && ctype_digit($jsonResponse['expires_in']) === false
            )
        ) {
            $this->logger->error('API tokens return a bad JSON content', ['json' => $jsonResponse]);
            throw UnexpectedResponseException::createInvalidJsonContent($body);
        }

        $config->setToken($jsonResponse['access_token']);
        $config->setExpirationDate(new DateTime());

        try {
            $config->getExpirationDate()->add(new DateInterval("PT".$jsonResponse['expires_in']."S"));
        } catch (Throwable $e) {
            $this->logger->error(
                'API tokens return an invalid expires_in value',
                ['expires_in' => $jsonResponse['expires_in']]
            );
            throw UnexpectedResponseException::createInvalidJsonContent($body);
        }
    }
}
