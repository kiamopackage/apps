<?php

namespace KiamoPackage\AppsBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @MappedSuperclass
 */
abstract class Config
{
    const ID = 1;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="Id", type="integer")
     */
    protected $id = self::ID;

    /**
     * @var bool
     * @ORM\Column(name="Enabled", type="boolean")
     */
    protected $enabled = false;

    /**
     * @var string|null
     * @ORM\Column(name="ClientId", type="string", length=255, nullable=true)
     */
    protected $clientId = null;

    /**
     * @var string|null
     * @ORM\Column(name="ClientSecret", type="string", length=255, nullable=true)
     */
    protected $clientSecret = null;

    /**
     * @var string|null
     * @ORM\Column(name="Token", type="string", length=255, nullable=true)
     */
    protected $token = null;

    /**
     * @var DateTime|null
     * @ORM\Column(name="ExpirationDate", type="datetime", nullable=true)
     */
    protected $expirationDate = null;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Config
     */
    public function setId(int $id): Config {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Config
     */
    public function setEnabled(bool $enabled): Config {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     * @return Config
     */
    public function setClientId(?string $clientId): Config {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientSecret(): ?string {
        return $this->clientSecret;
    }

    /**
     * @param string|null $clientSecret
     * @return Config
     */
    public function setClientSecret(?string $clientSecret): Config {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return Config
     */
    public function setToken(?string $token): Config {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpirationDate(): ?DateTime {
        return $this->expirationDate;
    }

    /**
     * @param DateTime|null $expirationDate
     * @return Config
     */
    public function setExpirationDate(?DateTime $expirationDate): Config {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
