<?php

namespace KiamoPackage\AppsBundle\Service\HttpRequest;

use KiamoPackage\AppsBundle\Exception\DatabaseException;
use KiamoPackage\AppsBundle\Exception\DisabledException;
use KiamoPackage\AppsBundle\Exception\InvalidTokenException;
use KiamoPackage\AppsBundle\Exception\NoTokenException;
use KiamoPackage\AppsBundle\Exception\SystemException;
use KiamoPackage\AppsBundle\Service\ConfigService;
use KiamoPackage\AppsBundle\Service\LoggerService;
use KiamoPackage\AppsBundle\Utility\HttpResponse;

class AuthenticateApiRequestService
{

    /** @var ApiRequestService */
    private $apiRequestService;

    /** @var ConfigService */
    private $configService;

    /** @var string[]|null */
    private $defaultHeaders = null;

    /** @var LoggerService */
    private $logger;

    /**
     * @param ApiRequestService $apiRequestService
     * @param ConfigService $configService
     * @param LoggerService $logger
     */
    function __construct(ApiRequestService $apiRequestService, ConfigService $configService, LoggerService $logger) {
        $this->configService = $configService;
        $this->apiRequestService = $apiRequestService;
        $this->logger = $logger;
    }

    /**
     * @param string $path Path from API domain without inital slash (ex: 'api/services/licenses')
     * @param array $data
     * @return HttpResponse
     * @throws DatabaseException Database exception
     * @throws DisabledException Kiamo Apps are desactived
     * @throws InvalidTokenException Expired token in database
     * @throws NoTokenException No token in database
     * @throws SystemException
     */
    public function get(string $path, array $data = []): HttpResponse {
        return $this->apiRequestService->doRequest(ApiRequestService::GET, $path, $data, $this->getHeaders());
    }

    /**
     * @param string $path Path from API domain without inital slash (ex: 'api/services/licenses')
     * @param array $data
     * @return HttpResponse
     * @throws DatabaseException Database exception
     * @throws DisabledException Kiamo Apps are desactived
     * @throws InvalidTokenException Expired token in database
     * @throws NoTokenException No token in database
     * @throws SystemException
     */
    public function post(string $path, array $data = []): HttpResponse {
        return $this->apiRequestService->doRequest(ApiRequestService::POST, $path, $data, $this->getHeaders());
    }

    /**
     * @return string[]
     * @throws DisabledException Kiamo Apps are desactived
     * @throws InvalidTokenException Expired token in database
     * @throws NoTokenException No token in database
     * @throws DatabaseException Database exception
     */
    private function getHeaders(): array {
        if ($this->defaultHeaders === null) {
            $config = $this->configService->getConfig();

            if ($config->isEnabled() === false) {
                $this->logger->info('Kiamo Apps are disabled');
                throw new DisabledException();
            }

            if ($config->getToken() === null) {
                $this->logger->info('No Kiamo Apps token available');
                throw new NoTokenException();
            }

            if ($config->getExpirationDate()->getTimestamp() < time()) {
                $this->logger->info('Kiamo Apps token expired');
                throw new InvalidTokenException();
            }

            $this->defaultHeaders = ['Authorization: Bearer '.$config->getToken()];
        }

        return $this->defaultHeaders;
    }
}
