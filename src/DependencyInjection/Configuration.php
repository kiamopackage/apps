<?php


namespace KiamoPackage\AppsBundle\DependencyInjection;

use InvalidArgumentException;
use KiamoPackage\AppsBundle\Entity\Config;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder('kiamo_package_apps');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('entity_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->always()
                        ->then(
                            function ($value) {
                                if (is_string($value) === false) {
                                    throw new InvalidArgumentException('entity_class must be a class name string (set to '.$value.')');
                                }

                                if (class_exists($value) === false) {
                                    throw new InvalidArgumentException('entity_class "'.$value.'" doesn\'t exist');
                                } elseif (is_a($value, Config::class, true) === false) {
                                    throw new InvalidArgumentException('entity_class "'.$value.'" doesn\'t extend '.Config::class);
                                }

                                return $value;
                            }
                        )
                        ->end()
                    ->end()
                ->scalarNode('host')
                    ->defaultValue('https://kls.kiamo.fr/')
                    ->validate()
                        ->always()
                        ->then(
                            function ($value) {
                                if (is_string($value) === false) {
                                    throw new InvalidArgumentException('host must be an URL string (set to '.$value.')');
                                }

                                return $value;
                            }
                        )
                        ->end()
                    ->end()
                ->integerNode('port')
                    ->defaultValue(443)
                    ->end()
                ->integerNode('timeout')
                    ->defaultValue(15)
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
