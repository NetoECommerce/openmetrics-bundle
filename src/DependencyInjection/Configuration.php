<?php declare(strict_types=1);

namespace Neto\OpenmetricsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('neto_openmetrics');
        $rootNode = $treeBuilder->getRootNode();

        $supportedTypes = ['in_memory', 'apcu', 'redis'];

        $rootNode
            ->children()
                ->scalarNode('namespace')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('type')
                    ->validate()
                        ->ifNotInArray($supportedTypes)
                        ->thenInvalid('The type %s is not supported. Please choose one of '.implode(', ', $supportedTypes))
                    ->end()
                    ->defaultValue('in_memory')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('redis')
                    ->children()
                        ->scalarNode('host')->end()
                        ->integerNode('port')
                            ->defaultValue(6379)
                        ->end()
                        ->floatNode('timeout')->end()
                        ->floatNode('read_timeout')
                            ->validate()
                                ->always()
                                // here we force casting `float` to `string` to avoid TypeError when working with Redis
                                // see for more details: https://github.com/phpredis/phpredis/issues/1538
                                ->then(function ($v) { return (string) $v; })
                            ->end()
                        ->end()
                        ->booleanNode('persistent_connections')->end()
                        ->scalarNode('password')->end()
                        ->integerNode('database')->end()
                    ->end()
                ->end()
                ->arrayNode('ignored_routes')
                    ->prototype('scalar')->end()
                    ->defaultValue(['prometheus_metrics'])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
