<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KiamoPackage\AppsBundle\DependencyInjection;

use Exception;
use KiamoPackage\AppsBundle\Service\ConfigService;
use KiamoPackage\AppsBundle\Service\HttpRequest\ApiRequestService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KiamoPackageAppsExtension extends Extension
{
    /**
     * @param array $configs An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('service.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);
        $definition = $container->getDefinition(ApiRequestService::class);
        $definition->setArgument('$host', $config['host']);
        $definition->setArgument('$port', $config['port']);
        $definition->setArgument('$timeout', $config['timeout']);

        $definition = $container->getDefinition(ConfigService::class);
        $definition->setArgument('$entityClass', $config['entity_class']);

        // Il est recommandé d'ajouter les classes qui comporte des annotations comme suit
        // pour optimiser la génération de cache
        // (c.f. https://symfony.com/doc/4.4/bundles/extension.html#adding-classes-to-compile)
        // (typiquement, les annotations de routing présentes dans les controllers
        // ou celle de mapping dans les entitées doctrine)
        $this->addAnnotatedClassesToCompile(
            [
                'KiamoPackage\\AppsBundle\\Entity\\',
            ]
        );
    }
}
