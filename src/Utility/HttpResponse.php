<?php


namespace KiamoPackage\AppsBundle\Utility;


class HttpResponse
{
    /** @var int */
    private $code;

    /** @var string */
    private $body;

    public function __construct(int $code = 200, string $body = '') {
        $this->code = $code;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getCode(): int {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getBody(): string {
        return $this->body;
    }
}
