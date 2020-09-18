<?php

namespace KiamoPackage\AppsBundle\Service\HttpRequest;

use KiamoPackage\AppsBundle\Exception\SystemException;
use KiamoPackage\AppsBundle\Service\LoggerService;
use KiamoPackage\AppsBundle\Utility\HttpResponse;

class ApiRequestService
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var int */
    private $timeout;

    /** @var LoggerService */
    private $logger;

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @param LoggerService $logger
     */
    function __construct(string $host, int $port, int $timeout, LoggerService $logger) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->logger = $logger;
    }

    /**
     * @param string $method
     * @param string $path Path from API domain without inital slash (ex: 'api/services/licenses')
     * @param array $data
     * @param array $headers
     * @param int|null $timeout
     * @return HttpResponse
     * @throws SystemException cURL exception
     */
    public function doRequest(
        string $method,
        string $path,
        array $data = [],
        array $headers = [],
        ?int $timeout = null
    ): HttpResponse {
        $ch = curl_init();

        if ($ch === false) {
            $this->logger->critical('Unable to initialize curl request');
            throw new SystemException("Unable to initialize curl request");
        }

        $options = [
            CURLOPT_URL => $this->host.$path,
            CURLOPT_PORT => $this->port,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => $timeout === null ? $this->timeout : $timeout,
        ];

        $options[CURLOPT_HTTPHEADER] = $headers;

        switch ($method) {
            case self::POST:
                $options[CURLOPT_POST] = true;
                break;
            case self::DELETE:
            case self::PUT:
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                break;
        }

        if (count($data) > 0) {
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = 'cURL request error: '.curl_error($ch);
            $this->logger->error($error);
            throw new SystemException($error);
        }

        $response = new HttpResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $response);

        curl_close($ch);

        return $response;
    }
}
