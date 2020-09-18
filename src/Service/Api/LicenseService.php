<?php


namespace KiamoPackage\AppsBundle\Service\Api;

use KiamoPackage\AppsBundle\Exception\ApiErrorException;
use KiamoPackage\AppsBundle\Exception\DatabaseException;
use KiamoPackage\AppsBundle\Exception\DisabledException;
use KiamoPackage\AppsBundle\Exception\InvalidTokenException;
use KiamoPackage\AppsBundle\Exception\NoTokenException;
use KiamoPackage\AppsBundle\Exception\SystemException;
use KiamoPackage\AppsBundle\Exception\UnexpectedResponseException;
use KiamoPackage\AppsBundle\Service\HttpRequest\AuthenticateApiRequestService;
use KiamoPackage\AppsBundle\Service\LoggerService;
use Throwable;

class LicenseService
{

    /** @var AuthenticateApiRequestService */
    private $requestService;

    /** @var LoggerService */
    private $logger;

    /**
     * @param LoggerService $logger
     * @param AuthenticateApiRequestService $requestService
     */
    public function __construct(AuthenticateApiRequestService $requestService, LoggerService $logger) {
        $this->requestService = $requestService;
        $this->logger = $logger;
    }

    /**
     * @param string $kiamoId
     * @return string[] Licenses string array
     * @throws ApiErrorException API return HTTP different of 200
     * @throws DisabledException Kiamo Apps are desactived
     * @throws InvalidTokenException Expired token in database
     * @throws NoTokenException No token in database
     * @throws SystemException
     * @throws UnexpectedResponseException API return a 200 HTTP code with an unexpected response
     * @throws DatabaseException
     */
    public function getDetails(string $kiamoId): array {
        $response = $this->requestService->get(
            'api/services/licenses',
            ['server_id' => $kiamoId,]
        );

        if ($response->getCode() !== 200) {
            $this->logger->error('API licenses return HTTP code '.$response->getCode());
            throw new ApiErrorException($response->getCode());
        } else {
            try {
                $parsedResponse = json_decode($response->getBody(), true);
            } catch (Throwable $e) {
                $this->logger->error('API licenses return an invalid JSON');
                throw UnexpectedResponseException::createJsonInvalid($response->getBody(), $e);
            }
        }

        if (
            is_array($parsedResponse) === false
            || array_key_exists('licenses', $parsedResponse) === false
            || is_array($parsedResponse['licenses']) === false
        ) {
            $this->logger->error('API licenses return a bad JSON content', ['json' => $response->getBody()]);
            throw UnexpectedResponseException::createInvalidJsonContent($response->getBody());
        }

        foreach ($parsedResponse['licenses'] as $value) {
            if (is_string($value) === false || empty($value) === true) {
                $this->logger->error('API licenses return a bad JSON content', ['json' => $response->getBody()]);
                throw UnexpectedResponseException::createInvalidJsonContent($response->getBody());
            }
        }

        return array_values($parsedResponse['licenses']);
    }
}
