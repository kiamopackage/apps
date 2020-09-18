<?php

namespace KiamoPackage\AppsBundle\Service\HttpRequest;

use KiamoPackage\AppsBundle\Exception\SystemException;
use KiamoPackage\AppsBundle\Utility\HttpResponse;

class UnauthenticateApiRequestService
{

    /** @var ApiRequestService */
    private $apiRequestService;

    /**
     * @param ApiRequestService $apiRequestService
     */
    function __construct(ApiRequestService $apiRequestService) {
        $this->apiRequestService = $apiRequestService;
    }

    /**
     * @param string $path Path from API domain without inital slash (ex: 'api/services/licenses')
     * @param array $data
     * @return HttpResponse
     * @throws SystemException
     */
    public function get(string $path, array $data = []): HttpResponse {
        return $this->apiRequestService->doRequest(ApiRequestService::GET, $path, $data);
    }

    /**
     * @param string $path
     * @param array $data
     * @return HttpResponse
     * @throws SystemException
     */
    public function post(string $path, array $data = []): HttpResponse {
        return $this->apiRequestService->doRequest(ApiRequestService::POST, $path, $data);
    }
}
