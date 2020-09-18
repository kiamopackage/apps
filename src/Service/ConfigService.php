<?php

namespace KiamoPackage\AppsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use KiamoPackage\AppsBundle\Entity\Config;
use KiamoPackage\AppsBundle\Exception\DatabaseException;
use LogicException;
use Throwable;

class ConfigService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var string */
    private $entityClass;

    /** @var Config */
    private $config = null;

    /** @var LoggerService */
    private $logger;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param string $entityClass
     * @param LoggerService $logger
     */
    public function __construct(ManagerRegistry $managerRegistry, string $entityClass, LoggerService $logger) {
        $this->em = $managerRegistry->getManagerForClass($entityClass);
        $this->entityClass = $entityClass;
        $this->logger = $logger;
    }

    /**
     * @return Config
     * @throws DatabaseException
     */
    public function getConfig(): Config {
        if ($this->config === null) {
            try {
                $this->config = $this->em->getRepository($this->entityClass)->find(Config::ID);
            } catch (Throwable $e) {
                $this->logger->error(
                    'An exception occurred while retrieving the configuration from the database',
                    ['exception' => $e->getMessage()]
                );

                throw new DatabaseException($e);
            }

            if ($this->config === null) {
                $this->logger->error('The database could not return configuration of Kiamo Apps');

                throw new DatabaseException();
            }
        }

        return $this->config;
    }

    /**
     * Fonction qui lance juste le flush de l'entity manager qui a normalement servi à récupérer
     * (ou, du moins, dans lequel a déjà été persisté) la configuration dans la fonction getConfig ci-dessus
     * @return void
     * @throws LogicException Code logic exception emit when getConfig method doesn't call before this
     * @throws DatabaseException Doctrine flush exception (Doctrine\ORM\ORMException...)
     */
    public function saveConfig(): void {
        if ($this->config === null) {
            throw new LogicException('Kiamo Apps configuration not initiated');
        }

        try {
            $this->em->flush();
        } catch (Throwable $e) {
            $this->logger->error(
                'An exception occurred while saving the configuration in the database',
                ['exception' => $e->getMessage()]
            );

            throw new DatabaseException($e);
        }
    }
}
